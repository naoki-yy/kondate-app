<script setup>
import Header from "./components/Header.vue";
import { RouterView } from "vue-router";
</script>

<template>
    <v-app>
        <Header />
        <RouterView />
    </v-app>
</template>
