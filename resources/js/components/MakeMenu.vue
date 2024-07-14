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
]);
const search = ref("");
const selected = ref([]);
const itemsPerPage = ref(5);
const pages = ref([
    { value: 5, title: "5" },
    { value: 10, title: "10" },
    { value: 15, title: "15" },
    { value: 20, title: "20" },
    { value: -1, title: "$vuetify.dataFooter.itemsPerPageAll" },
]);

const menus = ref([]);
const snackbar = ref(false);
const snackbarMessage = ref("");
const timeout = ref(1000);
const currentMenu = ref({
    sunday: "",
    monday: "",
    tuesday: "",
    wednesday: "",
    thursday: "",
    friday: "",
    saturday: "",
    totalAmount: "",
});

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
        snackbarMessage.value = "登録に失敗しました。";
        snackbar.value = true;
    }
};

const registerMenu = async () => {
    try {
        const response = await axios.post(
            "/api/menus/register",
            currentMenu.value
        );
        snackbarMessage.value = "登録が完了しました！";
        snackbar.value = true;
        fetchMenus();
    } catch (error) {
        console.error(error);
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

watch(selected, () => {
    if (selected.value.length > 0) {
        const selectedMenu = menus.value.find(
            (menu) => menu.id === selected.value[0]
        );
        if (selectedMenu) {
            currentMenu.value = { ...selectedMenu };
        }
    }
});

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
                        ><v-icon class="mr-2">mdi-silverware</v-icon></span
                    >
                    献立作成</v-card-title
                >
                <v-card-subtitle class="d-flex align-center"
                    ><v-icon class="mr-2">mdi-information-variant-circle</v-icon
                    >献立を作成します。過去作成した献立一覧から選ぶことができます。</v-card-subtitle
                >
                <v-sheet class="w-75 mx-auto mt-8 mb-7 border">
                    <v-card class="pb-3">
                        <div
                            class="d-flex justify-space-between align-center mt-2"
                        >
                            <div class="d-flex align-center">
                                <v-card-title class="font-weight-bold">
                                    今週の献立
                                </v-card-title>
                                <DateCalculation
                                    v-slot="{ startOfWeek, endOfWeek }"
                                >
                                    <div>
                                        {{ startOfWeek }} 〜
                                        {{ endOfWeek }}
                                    </div>
                                </DateCalculation>
                            </div>
                            <v-btn color="primary mr-5" @click="registerMenu"
                                >登録</v-btn
                            >
                        </div>

                        <v-card-text>
                            <v-table class="fixed-table">
                                <thead>
                                    <th>日</th>
                                    <th>月</th>
                                    <th>火</th>
                                    <th>水</th>
                                    <th>木</th>
                                    <th>金</th>
                                    <th>土</th>
                                    <th>金額</th>
                                </thead>
                                <tbody>
                                    <tr
                                        v-if="selected.length > 0"
                                        class="text-center"
                                    >
                                        <td>
                                            <input
                                                type="text"
                                                v-model="currentMenu.sunday"
                                                class="input-field"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                v-model="currentMenu.monday"
                                                class="input-field"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                v-model="currentMenu.tuesday"
                                                class="input-field"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                v-model="currentMenu.wednesday"
                                                class="input-field"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                v-model="currentMenu.thursday"
                                                class="input-field"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                v-model="currentMenu.friday"
                                                class="input-field"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                v-model="currentMenu.saturday"
                                                class="input-field"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                v-model="
                                                    currentMenu.formattedTotalAmount
                                                "
                                                class="input-field"
                                            />
                                        </td>
                                    </tr>
                                    <tr v-else class="text-center">
                                        <td colspan="8">
                                            メニューを選択してください
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
                        </v-card-text>
                    </v-card>
                </v-sheet>
                <v-sheet
                    class="mx-auto border mb-5"
                    style="height: 500px; overflow-y: auto"
                >
                    <v-card>
                        <v-card-title class="font-weight-bold">
                            献立一覧
                        </v-card-title>
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
                                show-select
                                :search="search"
                                select-strategy="single"
                                v-model:items-per-page="itemsPerPage"
                                :headers="headers"
                                :items="menus"
                                item-value="id"
                                :items-per-page-options="pages"
                                items-per-page-text="表示行数"
                                class="elevation-1"
                                v-model="selected"
                            ></v-data-table>
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
</template>

<style scoped>
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
