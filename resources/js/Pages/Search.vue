<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, Head, router } from "@inertiajs/vue3";
import Paginate from "@/Pages/Paginate.vue";
import Swal from "sweetalert2";

let props = defineProps({
    users: Object,
    search: String,
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
    <Head title="Users" />
    <AuthenticatedLayout :search="search" showMenu>
        <div class="flex items-center w-screen justify-center h-[91vh]">
            <div class="h-[80vh]">
                <div v-if="users.data.length > 0">
                    <Link
                        class="rounded bg-red-500 px-4 py-2"
                        :href="route('home')"
                        >Go back
                    </Link>
                    <div class="min-h-[60vh]">
                        <div class="grid grid-cols-3 w-[90vw] gap-4">
                            <div
                                v-for="user in users.data"
                                :id="user.email"
                                class="border rounded p-6 shadow-lg mt-5 h-[70px]"
                            >
                                <div class="flex justify-between">
                                    <Link
                                        :href="
                                            route('publicProfile', {
                                                id: user.id,
                                            })
                                        "
                                        as="button"
                                        >{{ user.name }}
                                    </Link>
                                    <button
                                        @click="sendRequest(user.id)"
                                        class="text-blue-500 hover:text-blue-600 active:scale-95 transition duration-200"
                                    >
                                        Send friend request
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Paginate class="mt-20" :links="users.links" />
                </div>
                <div
                    v-else
                    class="flex flex-col gap-4 justify-center items-center h-[80vh]"
                >
                    <h1 class="text-xl">No user found...</h1>
                    <Link
                        class="rounded bg-red-500 px-4 py-2"
                        :href="route('home')"
                        >Go back
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
