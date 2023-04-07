<script setup>
import { Head } from "@inertiajs/vue3";
import { watch, onBeforeMount, ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import Swal from "sweetalert2";
import Messages from "./Messages.vue";
import { useMessagesStore } from "@/Stores/useMessagesStore.js";

let props = defineProps({
    users: Array,
    friends: Array,
    messages: Array,
    toUser: Boolean,
    currentFriend: String,
    groups: Array,
    groupId: String,
});

let useMessages = useMessagesStore();
let filter = ref("");
let filteredFriends = ref(props.friends);
let filteredGroups = ref(props.groups);

watch(filter, async (newValue, oldValue) => {
    filteredFriends = props.friends.filter((user) =>
        user.name.toLowerCase().includes(newValue.toLowerCase())
    );
    filteredGroups = props.groups.filter((group) =>
        group.name.toLowerCase().includes(newValue.toLowerCase())
    );
});

onBeforeMount(() => {
    useMessages.setMessages(props.messages);
    useMessages.toUser = props.toUser;
    useMessages.group = props.groupId;
});

watch(
    () => props.messages,
    (newValue, oldValue) => {
        useMessages.setMessages(newValue);
    }
);
watch(
    () => props.groupId,
    (newValue, oldValue) => {
        useMessages.group = newValue;
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

function validateLeaveGroup(id, name) {
    swalCustom
        .fire({
            html: "Are you sure you want to leave " + name + "?",
            title: "Confirm",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel",
            reverseButtons: true,
        })
        .then((result) => {
            if (result.isConfirmed) {
                router.delete("/leaveGroup/" + id, {
                    preserveScroll: true,
                    only: ["groups"],
                });
            }
        });
}

function validateDeleteGroup(id, name) {
    swalCustom
        .fire({
            html: "Are you sure you want to delete " + name + "?",
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
                    only: ["groups"],
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
                    only: ["friends"],
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
            <div
                class="flex flex-col w-1/4 border-r-2 overflow-y-auto scrollbar-hide"
            >
                <div class="border-b-2 py-4 px-2">
                    <input
                        v-model="filter"
                        type="text"
                        placeholder="filter..."
                        class="py-2 px-2 border-2 border-gray-200 rounded-2xl w-full"
                    />
                </div>

                <!-- end search compt -->

                <!-- group list -->
                <div
                    v-for="(group, index) in filteredGroups"
                    :key="index"
                    :class="{
                        'flex border-b-2 items-center justify-between ': true,
                        'bg-blue-300': !toUser & (groupId == group.id),
                    }"
                >
                    <Link
                        :href="route('home', { group_id: group.id })"
                        preserve-scroll
                        preserve-state
                        class="flex flex-row justify-center items-center w-[90%] py-4 px-2"
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
                            <div class="text-sm">Nani</div>
                        </div>
                    </Link>
                    <Dropdown width="20" class="px-2">
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
                                    v-if="
                                        $page.props.auth.user.id == group.owner
                                    "
                                    @click="
                                        validateDeleteGroup(
                                            group.id,
                                            group.name
                                        )
                                    "
                                    class="text-red-500"
                                >
                                    Delete
                                </button>
                                <button
                                    v-else
                                    @click="
                                        validateLeaveGroup(group.id, group.name)
                                    "
                                    class="text-red-500"
                                >
                                    Leave
                                </button>
                            </div>
                        </template>
                    </Dropdown>
                </div>
                <!-- end group list -->

                <!-- user list -->
                <div
                    v-for="(friend, index) in filteredFriends"
                    :key="index"
                    :class="{
                        'flex border-b-2 items-center justify-between ': true,
                        'bg-blue-300': toUser & (currentFriend == friend.id),
                    }"
                >
                    <Link
                        :href="route('home', { id: friend.id })"
                        preserve-scroll
                        preserve-state
                        class="flex flex-row justify-center items-center w-[90%] px-2 py-4"
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
                        </div>
                    </Link>
                    <Dropdown width="20" class="px-2">
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

            <Messages :id="currentFriend" />
        </div>
    </div>
</template>
