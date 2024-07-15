<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import DateCalculation from "./DateCalculation.vue";

const selected = ref([]);

const current_menu = ref([]);
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

const fetchCurrentMenus = async () => {
    try {
        const response = await axios.get("/api/current_menus");
        current_menu.value = response.data;
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
    fetchCurrentMenus();
});
</script>

<template>
    <v-main>
        <v-container>
            <v-card elevation="4">
                <v-card-title class="text-h4 border d-flex align-center"
                    ><span class="text-h5"
                        ><v-icon class="mr-2">mdi-view-dashboard</v-icon></span
                    >
                    Top</v-card-title
                >
                <v-card-subtitle class="d-flex align-center"
                    ><v-icon class="mr-2">mdi-information-variant-circle</v-icon
                    >今週の献立・今日の献立を見る画面です。</v-card-subtitle
                >
                <v-sheet class="mx-auto mt-8 mb-7 border" width="90%">
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
                                    <tr>
                                        <td class="text-center">
                                            {{ current_menu.sunday }}
                                        </td>
                                        <td class="text-center">
                                            {{ current_menu.monday }}
                                        </td>
                                        <td class="text-center">
                                            {{ current_menu.tuesday }}
                                        </td>
                                        <td class="text-center">
                                            {{ current_menu.wednesday }}
                                        </td>
                                        <td class="text-center">
                                            {{ current_menu.thursday }}
                                        </td>
                                        <td class="text-center">
                                            {{ current_menu.friday }}
                                        </td>
                                        <td class="text-center">
                                            {{ current_menu.saturday }}
                                        </td>
                                        <td class="text-center">
                                            {{ current_menu.totalAmount }}
                                        </td>
                                    </tr>
                                    <!-- <tr v-else class="text-center">
                                        <td colspan="8">
                                            メニューを選択してください
                                        </td>
                                    </tr> -->
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
                            今日の献立
                        </v-card-title>
                        <v-card-text> </v-card-text>
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
