<script setup>
import Menu from "@/Pages/Menu.vue";
import { onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import { useMessagesStore } from "@/Stores/useMessagesStore";

let useMessages = useMessagesStore();
let props = defineProps({
    search: String,
});

onMounted(() => {
    window.Echo.private(
        "App.Models.User." + usePage().props.auth.user.id
    ).notification((notif) => {
        usePage().props.notifications.push(notif);
    });

    window.Echo.private("chat-" + usePage().props.auth.user.id).listen(
        "MessageSent",
        (e) => {
            if (e.from == useMessages.currentFriend)
                useMessages.addMessage(e.privateMessage);
        }
    );
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
            <Menu :search="search" />

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
