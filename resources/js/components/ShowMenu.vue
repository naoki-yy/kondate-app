<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import DateCalculation from "./DateCalculation.vue";

const headers = ref([
    { title: "No.", align: "end", sortable: true, key: "id" },
    { title: "日", align: "center", sortable: true, key: "sunday" },
    { title: "月", align: "center", sortable: true, key: "monday" },
    { title: "火", align: "center", sortable: true, key: "tuesday" },
    { title: "水", align: "center", sortable: true, key: "wednesday" },
    { title: "木", align: "center", sortable: true, key: "thursday" },
    { title: "金", align: "center", sortable: true, key: "friday" },
    { title: "土", align: "center", sortable: true, key: "saturday" },
    {
        title: "金額",
        align: "center",
        sortable: true,
        key: "formattedTotalAmount",
    },
    {
        title: "最終使用日",
        align: "center",
        sortable: true,
        key: "formattedLastUsedDate",
    },
    {
        title: "",
        align: "center",
        key: "actions",
    },
]);
const search = ref("");
const selected = ref([]);
const itemsPerPage = ref(20);
const pages = ref([
    { value: 5, title: "5" },
    { value: 10, title: "10" },
    { value: 15, title: "15" },
    { value: 20, title: "20" },
    { value: -1, title: "$vuetify.dataFooter.itemsPerPageAll" },
]);

const menus = ref([]);
// const currentMenu = ref({
//     sunday: "",
//     monday: "",
//     tuesday: "",
//     wednesday: "",
//     thursday: "",
//     friday: "",
//     saturday: "",
//     totalAmount: "",
// });
const register_editMenu = ref({});
const dialog = ref(false);
const confirmDialog = ref(false);
const deleteToItem = ref(null);
const dialogTitle = ref("");
const snackbar = ref(false);
const snackbarMessage = ref("");
const timeout = ref(2000);
const isEditMode = ref(false);

const fetchMenus = async () => {
    try {
        const response = await axios.get("/api/menus");
        menus.value = response.data.map((menu) => ({
            ...menu,
            formattedTotalAmount: formatCurrency(menu.totalAmount),
            formattedLastUsedDate: formatDate(menu.last_used_date),
        }));
    } catch (error) {
        console.error("Error registering menu:", error);
        snackbarMessage.value = "データ取得に失敗しました。";
        snackbar.value = true;
    }
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat("ja-JP", {
        style: "currency",
        currency: "JPY",
    }).format(value);
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = date.getMonth() + 1;
    const day = date.getDate();
    return `${year}年${month}月${day}日`;
};

const registerMenu = async () => {
    try {
        await axios.post("/api/menus/register", register_editMenu.value);
        snackbarMessage.value = "登録が完了しました！";
        snackbar.value = true;
        dialog.value = false;
        fetchMenus();
    } catch (error) {
        console.error(error);
    }
};

const openDialog = () => {
    dialogTitle.value = "新しい献立を追加";
    register_editMenu.value = {
        sunday: "",
        monday: "",
        tuesday: "",
        wednesday: "",
        thursday: "",
        friday: "",
        saturday: "",
        totalAmount: "",
    };
    dialog.value = true;
};

const editItem = (item) => {
    isEditMode.value = true;
    dialogTitle.value = "献立を編集";
    register_editMenu.value = { ...item };
    dialog.value = true;
};

const deleteItemConfirm = (item) => {
    deleteToItem.value = item;
    confirmDialog.value = true;
};

const deleteItem = async () => {
    try {
        await axios.delete(`/api/menus/${deleteToItem.value.id}`);
        snackbarMessage.value = "削除が完了しました。";
        snackbar.value = true;
        fetchMenus();
    } catch (error) {
        console.error("Error deleting menu:", error);
        snackbarMessage.value = "削除に失敗しました。";
        snackbar.value = true;
    } finally {
        confirmDialog.value = false;
        deleteToItem.value = null;
    }
};

const saveMenu = async () => {
    try {
        if (register_editMenu.value.id) {
            await axios.put(
                `/api/menus/${register_editMenu.value.id}`,
                register_editMenu.value
            );
            snackbarMessage.value = "更新が完了しました！";
            dialog.value = false;
        } else {
            await axios.post("/api/menus", register_editMenu.value);
            snackbarMessage.value = "登録が完了しました！";
        }
        dialog.value = false;
        snackbar.value = true;
        fetchMenus();
    } catch (error) {
        console.error("Error saving menu:", error);
        snackbarMessage.value = "保存に失敗しました。";
        snackbar.value = true;
    }
};

const closeDialog = () => {
    dialog.value = false;
};

onMounted(() => {
    fetchMenus();
});
</script>

<template>
    <v-main>
        <v-container>
            <v-card elevation="4">
                <v-card-title class="text-h4 border d-flex align-center"
                    ><span class="text-h5"
                        ><v-icon class="mr-2">mdi-menu-open</v-icon></span
                    >
                    献立一覧</v-card-title
                >
                <v-card-subtitle class="d-flex align-center"
                    ><v-icon class="mr-2">mdi-information-variant-circle</v-icon
                    >過去作成した献立を見ることができます。新規追加、編集、削除もここから行います。</v-card-subtitle
                >
                <v-sheet
                    class="mx-auto border mb-5"
                    style="height: 500px; overflow-y: auto"
                >
                    <v-card>
                        <v-card-text>
                            <v-text-field
                                v-model="search"
                                prepend-inner-icon="mdi-magnify"
                                variant="outlined"
                                label="検索"
                                single-line
                                hide-details
                                class="w-50"
                            ></v-text-field>
                            <v-data-table
                                density="compact"
                                :search="search"
                                v-model:items-per-page="itemsPerPage"
                                :headers="headers"
                                :items="menus"
                                item-value="id"
                                :items-per-page-options="pages"
                                items-per-page-text="表示行数"
                                class="elevation-1"
                                v-model="selected"
                            >
                                <template v-slot:item.sunday="{ item }">
                                    <a :href="`/menu/${item.sunday}`">{{
                                        item.sunday
                                    }}</a>
                                </template>
                                <template v-slot:item.monday="{ item }">
                                    <a :href="`/menu/${item.monday}`">{{
                                        item.monday
                                    }}</a>
                                </template>
                                <template v-slot:item.tuesday="{ item }">
                                    <a :href="`/menu/${item.tuesday}`">{{
                                        item.tuesday
                                    }}</a>
                                </template>
                                <template v-slot:item.wednesday="{ item }">
                                    <a :href="`/menu/${item.wednesday}`">{{
                                        item.wednesday
                                    }}</a>
                                </template>
                                <template v-slot:item.thursday="{ item }">
                                    <a :href="`/menu/${item.thursday}`">{{
                                        item.thursday
                                    }}</a>
                                </template>
                                <template v-slot:item.friday="{ item }">
                                    <a :href="`/menu/${item.friday}`">{{
                                        item.friday
                                    }}</a>
                                </template>
                                <template v-slot:item.saturday="{ item }">
                                    <a :href="`/menu/${item.saturday}`">{{
                                        item.saturday
                                    }}</a>
                                </template>
                                <template v-slot:top>
                                    <v-toolbar flat>
                                        <v-toolbar-title
                                            >献立一覧</v-toolbar-title
                                        >
                                        <v-divider
                                            class="mx-4"
                                            inset
                                            vertical
                                        ></v-divider>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            color="primary"
                                            dark
                                            @click="openDialog"
                                        >
                                            献立追加
                                        </v-btn>
                                    </v-toolbar>
                                </template>
                                <template v-slot:item.actions="{ item }">
                                    <v-icon
                                        class="me-4"
                                        @click="editItem(item)"
                                    >
                                        mdi-pencil
                                    </v-icon>
                                    <v-icon @click="deleteItemConfirm(item)">
                                        mdi-delete
                                    </v-icon>
                                </template>
                            </v-data-table>
                        </v-card-text>
                    </v-card>
                </v-sheet>
            </v-card>
        </v-container>
    </v-main>
    <v-snackbar v-model="snackbar" color="primary" :timeout="timeout">
        {{ snackbarMessage }}
        <template v-slot:actions>
            <v-btn color="white" variant="text" @click="snackbar = false">
                閉じる
            </v-btn>
        </template>
    </v-snackbar>
    <v-dialog v-model="dialog" max-width="1000px">
        <v-card>
            <v-card-title>
                <span class="text-h5">{{ dialogTitle }}</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="3">
                            <v-text-field
                                v-model="register_editMenu.sunday"
                                label="日"
                                clearable
                            ></v-text-field>
                        </v-col>
                        <v-col cols="3">
                            <v-text-field
                                v-model="register_editMenu.monday"
                                label="月"
                                clearable
                            ></v-text-field>
                        </v-col>
                        <v-col cols="3">
                            <v-text-field
                                v-model="register_editMenu.tuesday"
                                label="火"
                                clearable
                            ></v-text-field>
                        </v-col>
                        <v-col cols="3">
                            <v-text-field
                                v-model="register_editMenu.wednesday"
                                label="水"
                                clearable
                            ></v-text-field>
                        </v-col>
                        <v-col cols="3">
                            <v-text-field
                                v-model="register_editMenu.thursday"
                                label="木"
                                clearable
                            ></v-text-field>
                        </v-col>
                        <v-col cols="3">
                            <v-text-field
                                v-model="register_editMenu.friday"
                                label="金"
                                clearable
                            ></v-text-field>
                        </v-col>
                        <v-col cols="3">
                            <v-text-field
                                v-model="register_editMenu.saturday"
                                label="土"
                                clearable
                            ></v-text-field>
                        </v-col>
                        <v-col cols="3">
                            <v-text-field
                                v-model="register_editMenu.totalAmount"
                                label="金額"
                                clearable
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue-darken-1" variant="text" @click="closeDialog"
                    >キャンセル</v-btn
                >
                <v-btn
                    v-if="isEditMode === true"
                    color="blue-darken-1"
                    variant="text"
                    @click="saveMenu"
                    >保存</v-btn
                >
                <v-btn
                    v-else
                    color="blue-darken-1"
                    variant="text"
                    @click="registerMenu"
                    >追加</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!-- Confirm Dialog -->
    <v-dialog v-model="confirmDialog" max-width="600px">
        <v-card>
            <v-card-title class="text-h5">
                <v-icon class="mr-2">mdi-alert-circle</v-icon>
                確認
            </v-card-title>
            <v-card-text>
                <p>本当にこの献立を削除しますか？</p>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="grey"
                    variant="text"
                    @click="confirmDialog = false"
                    >キャンセル</v-btn
                >
                <v-btn color="red" variant="text" @click="deleteItem()"
                    >削除</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style scoped>
a {
    text-decoration: none;
    color: black;
}
.v-data-table {
    border: 1px solid #e0e0e0;
}

.v-data-table th,
.v-data-table td {
    border-bottom: 1px solid #e0e0e0;
}
.v-table th,
.v-table td {
    border: 1px solid #e0e0e0;
    padding: 8px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.fixed-table {
    table-layout: fixed;
    width: 100%;
}

.input-field {
    width: 100%;
    box-sizing: border-box;
    border: none;
    background-color: transparent;
    text-align: center;
    padding: 0;
}
.v-snackbar {
    justify-content: center;
}
</style>
