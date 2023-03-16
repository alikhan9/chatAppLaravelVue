<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";

let props = defineProps({
    friends: Array,
});

const swalCustom = Swal.mixin({
    customClass: {
        confirmButton: "btn bg-red-500 px-4 py-2 text-white rounded",
        cancelButton: "btn bg-gray-500 px-4 py-2 text-white mr-2 rounded",
    },
    buttonsStyling: false,
});

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
                    preserveState: true,
                });
            }
        });
}
</script>

<template>
    <Head title="Public Profile" />
    <AuthenticatedLayout showMenu>
        <div class="h-[80vh] flex justify-center gap-4 items-center">
            <div class="border rounded shadow-lg min-h-[31vh] min-w-[21vw]">
                <h1 class="px-8 pt-4 text-xl text-blue-500 text-center">
                    User info
                </h1>
                <div class="p-8 border-b border-dashed">
                    Name : {{ $page.props.auth.user.name }}
                </div>
                <div class="p-8 border-b border-dashed">
                    Email: {{ $page.props.auth.user.email }}
                </div>
                <div class="p-8">
                    Account created : {{ $page.props.auth.user.created_at }}
                </div>
            </div>
            <div
                class="border rounded shadow-lg max-h-[50vh] overflow-auto min-h-[31vh] min-w-[21vw]"
            >
                <h1 class="px-8 pt-4 text-xl text-blue-500 text-center">
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
                        class="flex justify-between border-b border-dashed p-8"
                    >
                        <Link
                            :href="route('publicProfile', { id: friend.id })"
                            as="button"
                            >{{ friend.name }}
                        </Link>
                        <button
                            @click="valideDeleteFriend(friend.id, friend.name)"
                            :class="{
                                'text-red-500 hover:text-red-600 active:scale-95 transition duration-200': true,
                            }"
                        >
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
