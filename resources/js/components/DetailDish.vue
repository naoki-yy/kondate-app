<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const dish = ref("");

onMounted(() => {
    dish.value = route.params.dish;
});
</script>

<template>
    <v-main>
        <v-container>
            <v-card>
                <v-card-title class="font-weight-bold">{{ dish }}</v-card-title>
            </v-card>
            <v-card-subtitle class="d-flex align-center"
                ><v-icon class="mr-2">mdi-information-variant-circle</v-icon
                >{{ dish }}を作る際の材料一覧ページです。</v-card-subtitle
            >
            <v-card-text>
                <v-sheet class="w-50">
                    <div class="mt-5 mb-6">参考リンク：https://instagram</div>
                    <h3>材料</h3>
                    <v-table class="mb-5" height="100px">
                        <thead>
                            <tr>
                                <th>材料名</th>
                                <th>必要数、グラム</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr></tr>
                            <tr></tr>
                        </tbody>
                    </v-table>
                </v-sheet>
            </v-card-text>
        </v-container>
    </v-main>
</template>
