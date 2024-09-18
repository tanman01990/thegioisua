<script setup>
import { storeToRefs } from 'pinia';

import { useAuthStore } from '@/stores';

const authStore = useAuthStore();
const { user } = storeToRefs(authStore);
</script>

<template>
    <div v-if="user">
        <h1>Hi {{user.firstName}}!</h1>
        <p>Milk inventory</p>
        <p><router-link to="/users">Manage Users</router-link></p>
        <p><router-link to="/upload">Upload File</router-link></p>
        <p><router-link to="/product">Product</router-link></p>
    </div>
</template>