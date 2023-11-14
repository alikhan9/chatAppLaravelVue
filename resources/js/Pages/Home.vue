<script setup>
import { Head } from "@inertiajs/vue3";
import { watch, onBeforeMount, ref } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import Swal from "sweetalert2";
import Messages from "./Messages.vue";
import { useMessagesStore } from "@/Stores/useMessagesStore.js";
import { DateTime } from "luxon";


let props = defineProps({
    users: Array,
    friends: Array,
    messages: Array,
    toUser: Boolean,
    currentChatId: String,
    groups: Array,
    groupId: String,
});

let useMessages = useMessagesStore();
let filter = ref("");
let filteredFriends = ref(props.friends);
let initialGroups = ref(props.groups);
let filteredGroups = ref(props.groups);

watch(
    () => useMessages.currentChatId,
    (newChatId, oldChatId) => {
        useMessages.currentChatId = newChatId;
    }
);

watch(filter, async (newValue, oldValue) => {
    filteredFriends = props.friends.filter((user) =>
        user.name.toLowerCase().includes(newValue.toLowerCase())
    );
    filteredGroups = initialGroups.value.filter((group) =>
        group.name.toLowerCase().includes(newValue.toLowerCase())
    );
});

watch(
    () => useMessages.userToAdd,
    (newFriend, oldFriend) => {
        if (newFriend?.id !== null) props.friends.push(newFriend);
    }
);
watch(initialGroups, (newGroups, oldGroups) => {
    filteredGroups.value = newGroups;
});
watch(
    () => useMessages.groupToAdd,
    (newGroup, oldGroup) => {
        if (newGroup.id !== null) props.groups.push(newGroup);
    }
);

watch(
    () => useMessages.userToRemove,
    (userToRemove, old) => {
        if (userToRemove?.id !== null)
            usePage().props.friends = props.friends.filter(
                (u) => u.id != userToRemove.id
            );
    }
);


onBeforeMount(() => {
    useMessages.setMessages(props.messages?.map(m => {
        m.created_at = convertDateToHuman(m.created_at)
        return m;
    }));
    useMessages.toUser = props.toUser;
    useMessages.group = props.groupId;
    useMessages.currentChatId = props.currentChatId;
});

function convertDateToHuman(date) {
    return DateTime.fromISO(date).toRelative();
}

watch(
    () => props.messages,
    (newValue, oldValue) => {
        useMessages.setMessages(newValue?.map(m => {
            m.created_at = convertDateToHuman(m.created_at)
            return m;
        }));
    }
);
watch(
    () => props.friends,
    (newValue, oldValue) => {
        filteredFriends.value = newValue;
    }
);
watch(
    () => props.groups,
    (newValue, oldValue) => {
        filteredGroups = newValue;
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
            confirmButtonText: "Leave",
            cancelButtonText: "Cancel",
            reverseButtons: true,
        })
        .then((result) => {
            if (result.isConfirmed) {
                axios.delete("/leaveGroup/" + id).then((response) => {
                    if (response.data === 1)
                        initialGroups.value = initialGroups.value.filter(
                            (g) => g.id != id
                        );
                    else console.log(nani);
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
                axios.delete("/deleteGroup/" + id).then((response) => {
                    if (response.data)
                        initialGroups.value = initialGroups.value.filter(
                            (g) => g.id != id
                        );
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
            <div class="flex flex-col w-1/4 border-r-2 overflow-y-auto scrollbar-hide">
                <div class="border-b-2 py-4 px-2">
                    <input name="filter" v-model="filter" type="text" placeholder="filter..."
                        class="py-2 px-2 border-2 border-gray-300 rounded-2xl w-full" />
                </div>

                <!-- end search compt -->

                <!-- group list -->
                <div v-for="(group, index) in filteredGroups" :key="index" :class="{
                    'flex border-b-2 items-center justify-between hover:bg-blue-200 transition': true,
                    'bg-blue-300 hover:bg-blue-400': !toUser & (groupId == group.id),
                }">
                    <Link :href="route('home', { group_id: group.id })" preserve-scroll preserve-state
                        class="flex flex-row justify-center items-center w-[90%] py-4 px-2">
                    <div class="w-1/4 flex justify-center items-center">
                        <unicon name="users-alt" fill="#eab440"
                            class="border object-cover h-10 w-10 bg-gray-100 border-spacing-8 rounded-full" />
                    </div>
                    <div class="w-full">
                        <div class="text-lg font-semibold">
                            {{ group.name }}
                        </div>
                    </div>
                    </Link>
                    <Dropdown width="20" class="px-2">
                        <template #trigger>
                            <span class="flex items-center hover:cursor-pointer">
                                <unicon name="ellipsis-v" fill="gray"></unicon>
                            </span>
                        </template>
                        <template #content>
                            <div class="text-center flex-col flex gap-2">
                                <Link :href="route('groupProfile', {
                                    id: group.id,
                                })
                                    " class="pb-2 w-full text-blue-500 shadow-md">
                                Profile
                                </Link>
                                <button v-if="$page.props.auth.user.id == group.owner
                                    " @click="
        validateDeleteGroup(
            group.id,
            group.name
        )
        " class="text-red-500">
                                    Delete
                                </button>
                                <button v-else @click="
                                    validateLeaveGroup(group.id, group.name)
                                    " class="text-red-500">
                                    Leave
                                </button>
                            </div>
                        </template>
                    </Dropdown>
                </div>
                <!-- end group list -->

                <!-- user list -->
                <div v-for="(friend, index) in filteredFriends" :key="index" :class="{
                    'flex border-b-2 items-center justify-between hover:bg-blue-200 transition': true,
                    'bg-blue-300 hover:bg-blue-400': toUser & (currentChatId == friend.id),
                }">
                    <Link :href="route('home', { id: friend.id })" preserve-scroll preserve-state
                        class="flex flex-row justify-center items-center w-[90%] px-2 py-4">
                    <div class="w-1/4 flex justify-center items-center">
                        <unicon name="user" fill="royalblue"
                            class="border object-cover h-10 w-10 bg-gray-100 border-spacing-8 rounded-full" />
                    </div>
                    <div class="w-full">
                        <div class="text-lg font-semibold">
                            {{ friend.name }}
                        </div>
                    </div>
                    </Link>
                    <Dropdown width="20" class="px-2">
                        <template #trigger>
                            <span class="flex items-center hover:cursor-pointer">
                                <unicon name="ellipsis-v" fill="gray"></unicon>
                            </span>
                        </template>
                        <template #content>
                            <div class="text-center flex-col flex gap-2">
                                <Link :href="route('publicProfile', {
                                    id: friend.id,
                                })
                                    " class="pb-2 w-full text-blue-500 shadow-md">
                                Profile
                                </Link>
                                <button @click="
                                    valideDeleteFriend(
                                        friend.id,
                                        friend.name
                                    )
                                    " class="text-red-500">
                                    Delete
                                </button>
                            </div>
                        </template>
                    </Dropdown>
                </div>
                <!-- end user list -->
            </div>

            <Messages :id="currentChatId" />
        </div>
    </div>
</template>
