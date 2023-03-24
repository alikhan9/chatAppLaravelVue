<script setup>
import { Head } from "@inertiajs/vue3";
import { watch, onBeforeMount } from "vue";
import { Link, router } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import Swal from "sweetalert2";
import Messages from "./Messages.vue";
import { useMessagesStore } from "@/Stores/useMessagesStore.js";

let useMessages = useMessagesStore();

let props = defineProps({
    users: Array,
    friends: Array,
    messages: Array,
    toUser: Boolean,
    currentFriend: String,
    groups: Array,
});

onBeforeMount(() => {
    useMessages.setMessages(props.messages);
    useMessages.toUser = props.toUser;
});

watch(
    () => props.messages,
    (newValue, oldValue) => {
        useMessages.setMessages(newValue);
    }
);
watch(
    () => props.toUser,
    (newValue, oldValue) => {
        useMessages.toUser = newValue;
    }
);

const swalCustom = Swal.mixin({
    customClass: {
        confirmButton: "btn bg-red-500 px-4 py-2 text-white rounded",
        cancelButton: "btn bg-gray-500 px-4 py-2 text-white mr-2 rounded",
    },
    buttonsStyling: false,
});

function valideDeleteGroup(id, name) {
    swalCustom
        .fire({
            html:
                "Are you sure you want to remove " +
                name +
                " from your friends list?",
            title: "Confirm",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel",
            reverseButtons: true,
        })
        .then((result) => {
            if (result.isConfirmed) {
                router.delete("/deleteGroup/" + id, {
                    preserveScroll: true,
                });
            }
        });
}
function valideDeleteFriend(id, name) {
    swalCustom
        .fire({
            html:
                "Are you sure you want to remove " +
                name +
                " from your friends list?",
            title: "Confirm",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel",
            reverseButtons: true,
        })
        .then((result) => {
            if (result.isConfirmed) {
                router.delete("/deleteFriend/" + id, {
                    preserveScroll: true,
                });
            }
        });
}
</script>

<template>
    <Head title="Home" />
    <div class="h-[91vh] shadow-lg rounded-lg">
        <!-- Chatting -->
        <div class="flex flex-row justify-between bg-white h-full">
            <!-- chat list -->
            <div
                class="flex flex-col w-2/5 border-r-2 overflow-y-auto scrollbar-hide"
            >
                <!-- search compt -->
                <div class="border-b-2 py-4 px-2">
                    <input
                        type="text"
                        placeholder="search chatting"
                        class="py-2 px-2 border-2 border-gray-200 rounded-2xl w-full"
                    />
                </div>
                <!-- end search compt -->
                <!-- user list -->
                <div
                    v-for="(group, index) in groups"
                    :key="index"
                    class="flex border-b-2 items-center justify-between py-4 px-2"
                >
                    <Link
                        :href="route('home', { group_id: group.id })"
                        preserve-scroll
                        preserve-state
                        class="flex flex-row justify-center items-center w-[90%]"
                    >
                        <div class="w-1/4">
                            <img
                                src="https://source.unsplash.com/_7LbC5J-jw4/600x600"
                                class="object-cover h-12 w-12 rounded-full"
                                alt=""
                            />
                        </div>
                        <div class="w-full">
                            <div class="text-lg font-semibold">
                                {{ group.name }}
                            </div>
                            <span class="text-gray-500"
                                >Pick me at 9:00 Am</span
                            >
                        </div>
                    </Link>
                    <Dropdown width="20">
                        <template #trigger>
                            <span
                                class="inline-flex rounded-md hover:cursor-pointer"
                            >
                                <unicon name="ellipsis-v" fill="gray"></unicon>
                            </span>
                        </template>
                        <template #content>
                            <div class="text-center flex-col flex gap-2">
                                <Link
                                    :href="
                                        route('groupProfile', {
                                            id: group.id,
                                        })
                                    "
                                    class="pb-2 w-full text-blue-500 shadow-md"
                                >
                                    Profile
                                </Link>
                                <button
                                    @click="
                                        valideDeleteGroup(group.id, group.name)
                                    "
                                    class="text-red-500"
                                >
                                    Delete
                                </button>
                            </div>
                        </template>
                    </Dropdown>
                </div>
                <div
                    v-for="(friend, index) in friends"
                    :key="index"
                    class="flex border-b-2 items-center justify-between py-4 px-2"
                >
                    <Link
                        :href="route('home', { id: friend.id })"
                        preserve-scroll
                        preserve-state
                        class="flex flex-row justify-center items-center w-[90%]"
                    >
                        <div class="w-1/4">
                            <img
                                src="https://source.unsplash.com/_7LbC5J-jw4/600x600"
                                class="object-cover h-12 w-12 rounded-full"
                                alt=""
                            />
                        </div>
                        <div class="w-full">
                            <div class="text-lg font-semibold">
                                {{ friend.name }}
                            </div>
                            <span class="text-gray-500"
                                >Pick me at 9:00 Am</span
                            >
                        </div>
                    </Link>
                    <Dropdown width="20">
                        <template #trigger>
                            <span
                                class="inline-flex rounded-md hover:cursor-pointer"
                            >
                                <unicon name="ellipsis-v" fill="gray"></unicon>
                            </span>
                        </template>
                        <template #content>
                            <div class="text-center flex-col flex gap-2">
                                <Link
                                    :href="
                                        route('publicProfile', {
                                            id: friend.id,
                                        })
                                    "
                                    class="pb-2 w-full text-blue-500 shadow-md"
                                >
                                    Profile
                                </Link>
                                <button
                                    @click="
                                        valideDeleteFriend(
                                            friend.id,
                                            friend.name
                                        )
                                    "
                                    class="text-red-500"
                                >
                                    Delete
                                </button>
                            </div>
                        </template>
                    </Dropdown>
                </div>

                <!-- end user list -->
            </div>
            <!-- end chat list -->
            <!-- message -->
            <Messages :id="currentFriend" />
            <!-- end message -->
            <div class="w-2/5 border-l-2 px-5">
                <div class="flex flex-col">
                    <div class="font-semibold text-xl py-4">
                        Mern Stack Group
                    </div>
                    <img
                        src="https://source.unsplash.com/L2cxSuKWbpo/600x600"
                        class="object-cover rounded-xl h-64"
                        alt=""
                    />
                    <div class="font-semibold py-4">Created 22 Sep 2021</div>
                    <div class="font-light">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Deserunt, perspiciatis!
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
