<script setup>
import { ref, onMounted, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

const route = useRoute();
const router = useRouter();
const search = ref("");
const tab = ref("");
const activeTab = ref(0);
const isEditMode = ref(false);
const isEditGenreMode = ref(false);
const genres = ref([]);
const fetch_genre_id = ref([]);
const dishes = ref([]);
const dialog = ref(false);
const genreDialog = ref(false);
const deleteGenreConfirmDialog = ref(false);
const dialogTitle = ref("");
const snackbar = ref(false);
const snackbarMessage = ref("");
const timeout = ref(1500);
const register_dish = ref({});
const register_genre = ref("");
const deleteToGenre = ref(null);
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
];

const genre_data = ref([{ text: "選択してください", value: null }]);

const openDialog = () => {
    dialogTitle.value = "新しい料理を追加";
    register_dish.value = {
        name: "",
        genre_id: "",
        icon: "",
        reference_url: "",
        description: "",
    };
    dialog.value = true;
};

const openGenreDialog = () => {
    dialogTitle.value = "新しいジャンルを追加";
    register_genre.value = {
        name: "",
    };
    genreDialog.value = true;
};

const editGenre = (genre) => {
    isEditGenreMode.value = true;
    dialogTitle.value = "ジャンルを編集";
    register_genre.value = { ...genre };
    genreDialog.value = true;
};

const closeDialog = () => {
    dialog.value = false;
};

const closeGenreDialog = () => {
    genreDialog.value = false;
};

const fetchDishes = async () => {
    try {
        const response = await axios.get("/api/dishes");
        dishes.value = response.data;
    } catch (error) {
        console.error("Error fetching dishes:", error);
    }
};

const fetchGenres = async () => {
    try {
        const response = await axios.get("/api/genres");
        genres.value = Object.values(response.data);
        fetch_genre_id.value = genres.value.map((item) => item.id);
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

const addDish = async () => {
    try {
        await axios.post("/api/dishes/register", register_dish.value);
        snackbarMessage.value = "登録が完了しました！";
        snackbar.value = true;
        dialog.value = false;
        window.location.reload();
        fetchDishes();
    } catch (error) {
        console.error(error);
    }
};

const addGenre = async () => {
    try {
        const response = await axios.post(
            "/api/genre/register",
            register_genre.value
        );
        snackbarMessage.value = "ジャンル登録が完了しました！";
        snackbar.value = true;
        genreDialog.value = false;
        genre_data.value.push({
            text: response.data.name,
            value: response.data.id,
        });
        fetchGenres();
    } catch (error) {
        console.error(error);
    }
};

const saveGenre = async () => {
    try {
        if (register_genre.value.id) {
            await axios.put(
                `/api/genre/${register_genre.value.id}`,
                register_genre.value
            );
            snackbarMessage.value = "更新が完了しました！";
            dialog.value = false;
        } else {
            await axios.post("/api/genre/register", register_genre.value);
            snackbarMessage.value = "登録が完了しました！";
        }
        genreDialog.value = false;
        snackbar.value = true;
        fetchGenres();
    } catch (error) {
        console.error("Error saving genre:", error);
        snackbarMessage.value = "保存に失敗しました。";
        snackbar.value = true;
    }
};

const deleteGenreConfirm = () => {
    deleteToGenre.value = genres.value[activeTab.value].id;
    deleteGenreConfirmDialog.value = true;
};

const deleteGenre = async () => {
    try {
        await axios.delete(`/api/genre/${deleteToGenre.value}`);
        snackbarMessage.value = "削除が完了しました。";
        snackbar.value = true;
        window.location.reload();
    } catch (error) {
        console.error("Error deleting Genre:", error);
        snackbarMessage.value = "削除に失敗しました。";
        snackbar.value = true;
    } finally {
        deleteGenreConfirmDialog.value = false;
        deleteToGenre.value = null;
    }
};

const fileteredDishes = computed(() => {
    const genre = fetch_genre_id.value[activeTab.value];

    return dishes.value.filter((dish) => {
        return dish.genre_id === genre && dish.name.includes(search.value);
    });
});
onMounted(() => {
    fetchDishes();
    fetchGenres();
    if (route.query.snackbarMessage) {
        snackbarMessage.value = route.query.snackbarMessage;
        snackbar.value = true;
    }
});
</script>

<template>
    <v-main>
        <v-container>
            <v-card elevation="4">
                <v-card-title class="text-h4 border d-flex align-center"
                    ><span class="text-h5"
                        ><v-icon class="mr-2">mdi-food-variant</v-icon></span
                    >
                    料理一覧</v-card-title
                >
                <v-card-subtitle class="d-flex align-center"
                    ><v-icon class="mr-2">mdi-information-variant-circle</v-icon
                    >過去作成した料理を見ることができます。新規追加、編集、削除もここから行います。</v-card-subtitle
                >
                <v-sheet
                    class="mx-auto border mb-5"
                    style="height: 600px; overflow-y: auto"
                >
                    <v-card>
                        <v-card-text>
                            <div
                                class="d-flex justify-space-between align-center"
                            >
                                <v-text-field
                                    v-model="search"
                                    prepend-inner-icon="mdi-magnify"
                                    variant="outlined"
                                    label="検索"
                                    single-line
                                    hide-details
                                    max-width="500px"
                                ></v-text-field>
                                <v-btn
                                    class="ml-5"
                                    color="primary"
                                    dark
                                    @click="openDialog"
                                >
                                    料理追加
                                </v-btn>
                            </div>
                            <v-tabs v-model="activeTab">
                                <template class="d-flex align-center">
                                    <template
                                        v-for="genre in genres"
                                        :key="genre.id"
                                    >
                                        <v-tab
                                            >{{ genre.name
                                            }}<v-icon
                                                class="ml-3"
                                                @click="editGenre(genre)"
                                            >
                                                mdi-pencil
                                            </v-icon></v-tab
                                        >
                                    </template>
                                    <span
                                        ><v-btn
                                            class="ml-1 px-0"
                                            elevation="0"
                                            @click="openGenreDialog"
                                            ><v-icon>mdi-plus</v-icon>
                                            ジャンル追加 </v-btn
                                        ><v-icon
                                            @click="deleteGenreConfirm()"
                                            class="ml-2"
                                        >
                                            mdi-delete
                                        </v-icon></span
                                    >
                                </template>
                            </v-tabs>
                            <v-window v-model="activeTab">
                                <v-window-item
                                    v-for="(tab, index) in genres"
                                    :key="tab"
                                    :value="index"
                                >
                                    <v-sheet>
                                        <v-row class="my-5">
                                            <v-col
                                                v-for="dish in fileteredDishes"
                                                :key="dish.id"
                                                cols="3"
                                            >
                                                <v-card
                                                    :href="`/dish/${dish.name}`"
                                                    class="border d-flex align-center justify-center"
                                                    elevation="5"
                                                    min-height="150px"
                                                >
                                                    <v-icon size="70">{{
                                                        dish.icon
                                                    }}</v-icon>
                                                    <v-card-title
                                                        class="ml-4 font-weight-bold"
                                                        >{{
                                                            dish.name
                                                        }}</v-card-title
                                                    >
                                                </v-card>
                                            </v-col>
                                        </v-row>
                                    </v-sheet>
                                </v-window-item>
                            </v-window>
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
    <v-dialog v-model="dialog" max-width="500px">
        <v-card>
            <v-card-title>
                <span class="text-h5">{{ dialogTitle }}</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="6">
                            <v-text-field
                                v-model="register_dish.name"
                                label="料理名"
                                clearable
                            ></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-select
                                :items="genre_data"
                                v-model="register_dish.genre_id"
                                item-title="text"
                                item-value="value"
                                label="ジャンル"
                                clearable
                            ></v-select>
                        </v-col>
                        <v-col cols="9">
                            <v-select
                                :items="icon_item_data"
                                v-model="register_dish.icon"
                                item-title="text"
                                item-value="value"
                                label="アイコン"
                                clearable
                            ></v-select>
                        </v-col>
                        <v-col cols="3">
                            <v-icon
                                :icon="register_dish.icon"
                                size="50"
                            ></v-icon>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                v-model="register_dish.reference_url"
                                label="参照URL"
                                clearable
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                v-model="register_dish.description"
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
                    @click="addDish"
                    >追加</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog v-model="genreDialog" max-width="500px">
        <v-card>
            <v-card-title>
                <span class="text-h5">{{ dialogTitle }}</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-text-field
                        v-model="register_genre.name"
                        label="ジャンル名"
                        clearable
                    ></v-text-field>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="blue-darken-1"
                    variant="text"
                    @click="closeGenreDialog"
                    >キャンセル</v-btn
                >
                <v-btn
                    v-if="isEditGenreMode === true"
                    color="blue-darken-1"
                    variant="text"
                    @click="saveGenre"
                    >保存</v-btn
                >
                <v-btn
                    v-else
                    color="blue-darken-1"
                    variant="text"
                    @click="addGenre"
                    >追加</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>

    <!-- Confirm Dialog -->
    <v-dialog v-model="deleteGenreConfirmDialog" max-width="600px">
        <v-card>
            <v-card-title class="text-h5">
                <v-icon class="mr-2">mdi-alert-circle</v-icon>
                確認
            </v-card-title>
            <v-card-text>
                <p>
                    ジャンル：{{ genres[activeTab].name }}を削除しますか？<br />
                    削除した場合、{{
                        genres[activeTab].name
                    }}で登録されている料理も削除されます。
                </p>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="grey"
                    variant="text"
                    @click="deleteGenreConfirmDialog = false"
                    >キャンセル</v-btn
                >
                <v-btn color="red" variant="text" @click="deleteGenre()"
                    >削除</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style scoped>
.v-snackbar {
    justify-content: center;
}
</style>
