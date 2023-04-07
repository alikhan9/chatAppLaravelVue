<script setup>
import Menu from "@/Pages/Menu.vue";
import { onMounted, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import { useMessagesStore } from "@/Stores/useMessagesStore";

let useMessages = useMessagesStore();

let props = defineProps({
    search: String,
});

watch(
    () => useMessages.group,
    (newGroup, oldGroup) => {
        if (Number.isInteger(parseInt(oldGroup))) {
            window.Echo.leave("chat-public-" + oldGroup);
            window.Echo.private("chat-public-" + newGroup).listen(
                "PublicMessageSent",
                (e) => {
                    useMessages.addMessage(e.publicMessage);
                }
            );
        }
    }
);

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

    if (!useMessages.toUser)
        window.Echo.private("chat-public-" + useMessages.group).listen(
            "PublicMessageSent",
            (e) => {
                useMessages.addMessage(e.publicMessage);
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
