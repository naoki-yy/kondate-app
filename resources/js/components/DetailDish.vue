<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

const route = useRoute();
const router = useRouter();
const dish_name = ref("");
const dish_id = ref(null);
const dish = ref({});

const materialDialog = ref(false);
const openMaterialDialogTitle = ref("");
const register_material = ref({});

const isMaterialEditMode = ref(false);

const snackbar = ref(false);
const snackbarMessage = ref("");
const timeout = ref(2000);

const dishEditDialog = ref(false);
const dishEditDialogTitle = ref("");
const edit_dish = ref({});

const icon_item_data = [
    { text: "ハンバーガー", value: "mdi-hamburger" },
    { text: "お肉", value: "mdi-food-steak" },
    { text: "りんご", value: "mdi-food-apple-outline" },
    { text: "チキンレッグ", value: "mdi-food-drumstick" },
    { text: "フライパン", value: "mdi-food-fork-drink" },
    { text: "アイスクリーム", value: "mdi-ice-cream" },
    { text: "ホットドッグ", value: "mdi-hotdog" },
    { text: "ピザ", value: "mdi-pizza" },
    { text: "サンドイッチ", value: "mdi-sandwich" },
    { text: "寿司", value: "mdi-sushi" },
    { text: "ケーキ", value: "mdi-cake" },
    { text: "コーヒー", value: "mdi-coffee" },
    { text: "ワイン", value: "mdi-glass-wine" },
    { text: "ジュース", value: "mdi-glass-cocktail" },
    { text: "マクドナルド", value: "mdi-food-outline" },
];

const genre_data = ref([{ text: "選択してください", value: null }]);
const genres = ref([]);

const deleteDishConfirmDialog = ref(false);
const deleteToDish = ref({});

const fetchDishId = async () => {
    try {
        const response = await axios.get(`/api/dish-id/${dish_name.value}`);
        dish_id.value = response.data.id;
        if (dish_id.value) {
            fetchDish();
        } else {
            router.push({
                name: "ShowCooking",
                query: {
                    snackbarMessage: `${dish_name.value}は存在しません。`,
                },
            });
        }
    } catch (error) {
        console.error("Error fetching dish ID:", error);
    }
};

const fetchDish = async () => {
    try {
        const response = await axios.get(`/api/dish/${dish_id.value}`);
        dish.value = response.data;
        console.log(dish.value);
    } catch (error) {
        console.error("Error fetching dish:", error);
    }
};

const fetchGenres = async () => {
    try {
        const response = await axios.get("/api/genres");
        genres.value = Object.values(response.data);
        genre_data.value = [
            { text: "選択してください", value: null },
            ...genres.value.map((item) => ({
                text: item.name,
                value: item.id,
            })),
        ];
    } catch (error) {
        console.error("Error fetching dishes:", error);
    }
};

const addMaterial = async () => {
    try {
        await axios.post("/api/material/register", register_material.value);
        materialDialog.value = false;
        snackbarMessage.value = "材料を追加しました！";
        snackbar.value = true;
        fetchDishId();
    } catch (error) {
        console.error("Error:", error);
    }
};

const saveMaterial = async () => {
    try {
        if (register_material.value.id) {
            await axios.put(
                `/api/material/${register_material.value.id}`,
                register_material.value
            );
            snackbarMessage.value = "材料を更新しました！";
            materialDialog.value = false;
        } else {
            await axios.post("/api/material/register", register_material.value);
            snackbarMessage.value = "材料を追加しました！";
        }
        materialDialog.value = false;
        snackbar.value = true;
        fetchDishId();
    } catch (error) {
        console.error("Error saving material:", error);
        snackbarMessage.value = "保存に失敗しました。";
        snackbar.value = true;
    }
};

const editMaterial = (material) => {
    openMaterialDialogTitle.value = "材料編集";
    register_material.value = { ...material };
    isMaterialEditMode.value = true;
    materialDialog.value = true;
};

const deleteMaterial = async (material) => {
    try {
        if (material.id) {
            await axios.delete(`/api/material/${material.id}`, material);
            snackbarMessage.value = "削除完了しました!";
            snackbar.value = true;
            fetchDishId();
        } else {
            console.log("指定した材料はありません");
        }
    } catch (error) {
        console.error("Error delete material:", error);
        snackbarMessage.value = "削除に失敗しました。";
        snackbar.value = true;
    }
};

const editDish = (dish) => {
    dishEditDialogTitle.value = "料理の編集";
    let target_icon = icon_item_data.find((icon) => icon.value === dish.icon);
    if (target_icon) {
        //何もしない
    } else {
        target_icon = "選択してください";
    }
    edit_dish.value = {
        id: dish.id,
        name: dish.name,
        genre_id: dish.genre_id,
        icon: target_icon,
        reference_url: dish.reference_url,
        description: dish.description,
    };
    dishEditDialog.value = true;
};

const saveDish = async () => {
    edit_dish.value.icon = isObject(edit_dish.value.icon);

    try {
        if (edit_dish.value) {
            const response = await axios.put(
                `/api/dish/${edit_dish.value.id}`,
                edit_dish.value
            );
            snackbarMessage.value = "更新が完了しました！";
            dishEditDialog.value = false;
            fetchDishId();
        }
    } catch (error) {
        console.error("Error saving dish:", error);
        snackbarMessage.value = "保存に失敗しました。";
        snackbar.value = true;
    }
};

const deleteDishConfirm = (dish) => {
    deleteToDish.value = dish;
    deleteDishConfirmDialog.value = true;
};

const deleteDish = async () => {
    try {
        if (dish.value.id) {
            const delete_dish_name = deleteToDish.value.name;
            await axios.delete(`/api/dish/${deleteToDish.value.id}`);
            deleteDishConfirmDialog.value = false;
            router.push({
                name: "ShowCooking",
                query: {
                    snackbarMessage: `${delete_dish_name}を削除しました!`,
                },
            });
        } else {
            console.log("指定した料理はありません");
        }
    } catch (error) {
        console.error("Error delete dish:", error);
        snackbarMessage.value = "削除に失敗しました。";
        snackbar.value = true;
    }
};

const openAddDialog = () => {
    openMaterialDialogTitle.value = "材料追加";
    register_material.value = {
        dish_id: dish_id.value,
        material: "",
        amount: "",
    };

    materialDialog.value = true;
};

const closeMaterialDialog = () => {
    materialDialog.value = false;
};

const closeDialog = () => {
    dishEditDialog.value = false;
};

const isObject = (variable) => {
    if (typeof variable === "string") {
        return variable;
    } else if (typeof variable === "object") {
        return variable.value;
    } else {
        return;
    }
};

const genre_name_value = computed(() => {
    const genre_name = genre_data.value.find(
        (genre) => genre.value === dish.value.genre_id
    );
    return genre_name ? genre_name.text : "ジャンルなし";
});

onMounted(() => {
    dish_name.value = route.params.dish;
    fetchDishId();
    fetchGenres();
});
</script>

<template>
    <v-main>
        <v-container>
            <v-card class="d-flex align-center justify-space-between py-2 mb-1">
                <v-card-title class="font-weight-bold d-flex align-center"
                    ><div class="d-flex align-end">
                        <v-icon class="mr-3 mb-1" size="30">{{
                            dish.icon
                        }}</v-icon>
                        <div class="text-h4 font-weight-bold">
                            {{ dish.name }}
                        </div>
                        <div class="ml-6">ー {{ genre_name_value }}</div>
                    </div>
                </v-card-title>
                <div>
                    <v-icon class="ml-3" size="30" @click="editDish(dish)">
                        mdi-pencil </v-icon
                    ><v-icon
                        @click="deleteDishConfirm(dish)"
                        class="ml-4 mr-4"
                        size="30"
                    >
                        mdi-delete
                    </v-icon>
                </div>
            </v-card>
            <v-card-subtitle class="d-flex align-center"
                ><v-icon class="mr-2">mdi-information-variant-circle</v-icon
                >{{ dish.name }}を作る際の材料一覧ページです。</v-card-subtitle
            >
            <v-card-text>
                <div class="font-weight-bold">
                    参考リンク：<a class="mt-5 mb-6" :href="dish.reference_url">
                        {{ dish.reference_url }}
                    </a>
                </div>
                <v-sheet class="w-50 mt-6">
                    <v-card>
                        <v-card-title
                            class="d-flex justify-space-between align-center bg-grey-lighten-3"
                        >
                            <h3>材料</h3>
                            <v-btn
                                class="ml-5"
                                color="primary"
                                dark
                                @click="openAddDialog()"
                            >
                                材料追加
                            </v-btn>
                        </v-card-title>
                        <v-table class="mb-5" height="500px">
                            <thead>
                                <tr>
                                    <th
                                        class="font-weight-bold"
                                        style="width: 40%"
                                    >
                                        材料名
                                    </th>
                                    <th
                                        class="font-weight-bold"
                                        style="width: 40%"
                                    >
                                        必要数・グラム
                                    </th>
                                    <th style="width: 20%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="material in dish.materials"
                                    :key="material.id"
                                >
                                    <td>{{ material.material }}</td>
                                    <td>{{ material.amount }}</td>
                                    <td>
                                        <v-icon
                                            class=""
                                            size="20"
                                            @click="editMaterial(material)"
                                        >
                                            mdi-pencil </v-icon
                                        ><v-icon
                                            @click="deleteMaterial(material)"
                                            class="ml-5"
                                            size="20"
                                        >
                                            mdi-delete
                                        </v-icon>
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </v-card>
                </v-sheet>
            </v-card-text>
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
    <!-- 材料追加、編集モーダル -->
    <v-dialog v-model="materialDialog" max-width="500px">
        <v-card>
            <v-card-title>
                <span class="text-h5">{{ openMaterialDialogTitle }}</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="6">
                            <v-text-field
                                v-model="register_material.material"
                                label="材料名 (必須)"
                                clearable
                            ></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field
                                v-model="register_material.amount"
                                label="必要量 (任意)"
                                clearable
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="blue-darken-1"
                    variant="text"
                    @click="closeMaterialDialog"
                    >キャンセル</v-btn
                >
                <v-btn
                    v-if="isMaterialEditMode === true"
                    color="blue-darken-1"
                    variant="text"
                    @click="saveMaterial"
                    >保存</v-btn
                >
                <v-btn
                    v-else
                    color="blue-darken-1"
                    variant="text"
                    @click="addMaterial"
                    >追加</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!-- 料理編集モーダル -->
    <v-dialog v-model="dishEditDialog" max-width="500px">
        <v-card>
            <v-card-title>
                <span class="text-h5">{{ dishEditDialogTitle }}</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="6">
                            <v-text-field
                                v-model="edit_dish.name"
                                label="料理名"
                                clearable
                            ></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-select
                                :items="genre_data"
                                v-model="edit_dish.genre_id"
                                item-title="text"
                                item-value="value"
                                label="ジャンル"
                                clearable
                            ></v-select>
                        </v-col>
                        <v-col cols="9">
                            <v-select
                                :items="icon_item_data"
                                v-model="edit_dish.icon"
                                item-title="text"
                                item-value="value"
                                label="アイコン"
                                clearable
                            ></v-select>
                        </v-col>
                        <v-col
                            v-if="edit_dish.icon.value === dish.icon"
                            cols="3"
                        >
                            <v-icon :icon="dish.icon" size="50"></v-icon>
                        </v-col>
                        <v-col v-else cols="3">
                            <v-icon :icon="edit_dish.icon" size="50"></v-icon>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                v-model="edit_dish.reference_url"
                                label="参照URL"
                                clearable
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                v-model="edit_dish.description"
                                label="説明"
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
                <v-btn color="blue-darken-1" variant="text" @click="saveDish"
                    >保存</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!-- Confirm Dialog -->
    <v-dialog v-model="deleteDishConfirmDialog" max-width="600px">
        <v-card>
            <v-card-title class="text-h5">
                <v-icon class="mr-2">mdi-alert-circle</v-icon>
                削除確認
            </v-card-title>
            <v-card-text>
                <p>
                    {{ dish.name }}を削除しますか？<br />
                    削除した場合、登録されている材料も削除されます。
                </p>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="grey"
                    variant="text"
                    @click="deleteDishConfirmDialog = false"
                    >キャンセル</v-btn
                >
                <v-btn color="red" variant="text" @click="deleteDish()"
                    >削除</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style scoped>
a {
    text-decoration: none;
    color: red;
}
</style>
