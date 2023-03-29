<script setup>
import { Head, Link, router } from "@inertiajs/vue3";

let props = defineProps({
    members: Array,
    group: Object,
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
</script>

<template>
    <Head title="Public Profile" />
    <div class="h-[80vh] flex justify-center gap-4 items-center">
        <div class="border rounded shadow-lg min-h-[31vh]">
            <h1 class="px-8 pt-4 text-xl text-blue-500">User info</h1>
            <div class="p-8 border-b border-dashed">Name : {{ user.name }}</div>
            <div class="p-8 border-b border-dashed">
                Email: {{ user.email }}
            </div>
            <div class="p-8">Account created : {{ user.created_at }}</div>
        </div>
        <div
            class="border rounded shadow-lg max-h-[50vh] overflow-auto min-h-[31vh] min-w-[21vw]"
        >
            <h1 class="px-8 pt-4 text-xl relative text-blue-500 text-center">
                <span class="relative">
                    Friends
                    <p
                        class="absolute top-[-10px] text-blue-500 right-[-12px] text-base"
                    >
                        {{ friends.length }}
                    </p>
                </span>
            </h1>
            <div v-for="(friend, index) in friends" :key="index">
                <div
                    v-if="$page.props.auth.user.id != friend.id"
                    class="flex justify-between border-b border-dashed p-8"
                >
                    <Link
                        :href="route('publicProfile', { id: friend.id })"
                        as="button"
                        >{{ friend.name }}
                    </Link>
                    <button
                        @click="sendRequest(friend.id)"
                        :class="{
                            'text-blue-500 hover:text-blue-600 active:scale-95 transition duration-200': true,
                            'text-gray-500 hover:text-gray-500 cursor-not-allowed active:scale-100 opacity-70':
                                user_friends.filter((u) => u.id === friend.id)
                                    .length === 1,
                        }"
                        :disabled="
                            user_friends.filter((u) => u.id === friend.id)
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
