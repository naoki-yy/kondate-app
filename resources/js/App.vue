<script setup>
import Header from "./components/Header.vue";
import { RouterView } from "vue-router";
import Footer from "./components/Footer.vue";
</script>

<template>
    <v-app>
        <Header />
        <RouterView />
        <Footer />
    </v-app>
</template>
