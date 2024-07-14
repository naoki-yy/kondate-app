<script setup>
import { ref } from "vue";
const drawer = ref(false);
</script>

<template>
    <v-app-bar color="primary" :elevation="2">
        <template v-slot:prepend>
            <v-app-bar-nav-icon
                variant="text"
                @click.stop="drawer = !drawer"
            ></v-app-bar-nav-icon>
        </template>

        <v-app-bar-title>Kondate ー 献立 ー </v-app-bar-title>

        <template v-slot:append>
            <v-btn> サインイン </v-btn>

            <v-btn>
                <v-icon> mdi-home </v-icon>
            </v-btn>
        </template>
    </v-app-bar>
    <v-navigation-drawer v-model="drawer">
        <v-list>
            <router-link to="/" custom v-slot="{ navigate }">
                <v-list-item
                    prepend-icon="mdi-view-dashboard"
                    title="Top"
                    value="top"
                    @click="navigate"
                ></v-list-item>
            </router-link>
            <router-link to="/make-menu" custom v-slot="{ navigate }">
                <v-list-item
                    prepend-icon="mdi-silverware"
                    title="献立作成"
                    value="make_menu"
                    @click="navigate"
                ></v-list-item>
            </router-link>
            <router-link to="/generate-menu" custom v-slot="{ navigate }">
                <v-list-item
                    prepend-icon="mdi-nutrition"
                    title="献立生成 CommingSoon!"
                    value="generate_menu"
                    @click="navigate"
                    disabled
                ></v-list-item>
            </router-link>
            <router-link to="/show-menu" custom v-slot="{ navigate }">
                <v-list-item
                    prepend-icon="mdi-menu-open"
                    title="献立一覧"
                    value="show_menu"
                    @click="navigate"
                ></v-list-item>
            </router-link>
            <router-link to="/show-cooking" custom v-slot="{ navigate }">
                <v-list-item
                    prepend-icon="mdi-food-variant"
                    title="料理一覧"
                    value="show_cooking"
                    @click="navigate"
                ></v-list-item>
            </router-link>
        </v-list>
    </v-navigation-drawer>
</template>
