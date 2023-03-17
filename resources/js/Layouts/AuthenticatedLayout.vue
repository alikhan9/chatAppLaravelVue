<script setup>
import Menu from "@/Pages/Menu.vue";
import { onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";

let props = defineProps({
    showMenu: Boolean,
    search: String,
});

onMounted(() => {
    window.Echo.private(
        "App.Models.User." + usePage().props.auth.user.id
    ).notification((notif) => {
        usePage().props.notifications.push(notif);
    });
});
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>
            <Menu v-if="showMenu" :search="search" />

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
