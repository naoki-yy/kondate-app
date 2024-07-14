<template>
    <div>
        <slot
            :start-of-week="formattedStartOfWeek"
            :end-of-week="formattedEndOfWeek"
        ></slot>
    </div>
</template>

<script setup>
import { ref } from "vue";

const formatDateCurrentWeek = (dateString) => {
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = date.getMonth() + 1;
    const day = date.getDate();
    return `${month}/${day}`;
};

const currentDate = new Date();
const startOfWeek = new Date(currentDate);
const endOfWeek = new Date(currentDate);
startOfWeek.setDate(currentDate.getDate() - currentDate.getDay());
endOfWeek.setDate(currentDate.getDate() + (6 - currentDate.getDay()));
const formattedStartOfWeek = formatDateCurrentWeek(startOfWeek);
const formattedEndOfWeek = formatDateCurrentWeek(endOfWeek);
</script>
