<script setup>
import { useAuthStore } from '@/stores';

const authStore = useAuthStore();
</script>

<template>
    <nav v-show="authStore.user" class="navbar navbar-expand navbar-dark bg-dark">

        <div class="navbar-nav">
            <router-link to="/" class="nav-item nav-link">Home</router-link>
            <router-link to="/users" class="nav-item nav-link">Users</router-link>
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                   Nhập File
                </a>
                <ul class="dropdown-menu">
                    <li><router-link to="/upload" class="dropdown-item">Xuất Kho</router-link></li>
                    <li><router-link to="/stockupload" class="dropdown-item">Nhập Kho</router-link></li>
                </ul>
            </div>
            <router-link to="/product" class="nav-item nav-link">Sản phẩm</router-link>
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                   Nhập Xuất Kho
                </a>
                <ul class="dropdown-menu">
                    <li><router-link to="/transaction" class="dropdown-item">Hàng  Nhập Kho</router-link></li>
                    <li><router-link to="/transaction/out" class="dropdown-item">Hàng  Xuất Kho </router-link></li>
                    <li><router-link to="/transaction/export" class="dropdown-item">Export file</router-link></li>
                </ul>
            </div>

            
            <button @click="authStore.logout()" class="btn btn-link nav-item nav-link">Logout</button>
        </div>
    </nav>
</template>