<script setup>
import { Head, router, Link } from "@inertiajs/vue3";
import { DateTime } from "luxon";

let props = defineProps({
    members: Array,
    group: Object,
    user_friends: Array,
});

function sendRequest(id) {
    router.post(
        "/addFriend",
        { friend_id: id },
        {
            preserveState: false,
            onFinish: () => {
                Swal.fire({
                    position: "top-end",
                    width: 400,
                    icon: "success",
                    text: "Friend request sent.",
                    timer: 1000,
                    showConfirmButton: false,
                });
            },
        }
    );
}

function convertDateToHuman(date) {
    return DateTime.fromISO(date).toRelative();
}
</script>

<template>
    <Head title="Public Profile" />
    <div class="h-[80vh] flex justify-center gap-4 items-center">
        <div class="border rounded shadow-lg min-h-[31vh]">
            <h1 class="px-8 pt-4 text-xl text-blue-500">User info</h1>
            <div class="p-8 border-b border-dashed">
                Name : {{ group.name }}
            </div>
            <div class="p-8">
                Group created : {{ convertDateToHuman(group.created_at) }}
            </div>
            <div class="p-8">
                Owner :
                <Link
                    :href="
                        route('publicProfile', {
                            id: group.user.id,
                        })
                    "
                    as="button"
                    >{{ group.user.name }}
                </Link>
            </div>
        </div>
        <div
            class="border rounded shadow-lg max-h-[50vh] overflow-auto min-h-[31vh] min-w-[21vw] scrollbar-hide"
        >
            <h1 class="px-8 pt-4 text-xl relative text-blue-500 text-center">
                <span class="relative">
                    Members
                    <p
                        class="absolute top-[-10px] text-blue-500 right-[-12px] text-base"
                    >
                        {{ members.length }}
                    </p>
                </span>
            </h1>
            <div v-for="(member, index) in members" :key="index">
                <div
                    v-if="$page.props.auth.user.id != member.user.id"
                    class="flex justify-between border-b border-dashed p-8"
                >
                    <Link
                        :href="route('publicProfile', { id: member.user.id })"
                        as="button"
                        >{{ member.user.name }}
                    </Link>
                    <button
                        @click="sendRequest(member.user.id)"
                        :class="{
                            'text-blue-500 hover:text-blue-600 active:scale-95 transition duration-200': true,
                            'text-gray-500 hover:text-gray-500 cursor-not-allowed active:scale-100 opacity-70':
                                user_friends.filter(
                                    (u) => u.id === member.user.id
                                ).length === 1,
                        }"
                        :disabled="
                            user_friends.filter((u) => u.id === member.user.id)
                                .length === 1
                        "
                    >
                        Send friend request
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
