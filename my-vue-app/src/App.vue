<style>
@import '../node_modules/@syncfusion/ej2-base/styles/material.css';
@import '../node_modules/@syncfusion/ej2-buttons/styles/material.css';
@import '../node_modules/@syncfusion/ej2-inputs/styles/material.css';
@import '../node_modules/@syncfusion/ej2-popups/styles/material.css';
@import "../node_modules/@syncfusion/ej2-vue-calendars/styles/material.css";
</style>

<script setup>



import { Nav, Alert } from '@/components';
import { useAuthStore } from '@/stores';
import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the grid
import "ag-grid-community/styles/ag-theme-quartz.css"; // Optional Theme applied to the grid
const authStore = useAuthStore();
</script>

<template>

    <body class="dx-viewport">
        <div class="app-container" :class="authStore.user && 'bg-light'">
            <Nav />
            <Alert />
            <div class="fullscreen-container">
                <router-view />
            </div>
        </div>
    </body>
</template>

<style>
@import '@/assets/base.css';
</style>