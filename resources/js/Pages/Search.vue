<script setup>
import { Link, Head } from "@inertiajs/vue3";

import Users from "./Users.vue";

let props = defineProps({
    users: Object,
    groups: Object,
});
</script>
<template>
    <Head title="Users" />

    <div class="flex items-center w-screen justify-center h-[91vh]">
        <div class="h-[80vh]">
            <div v-if="users.data.length > 0">
                <div class="relative mb-3">
                    <Link
                        class="rounded bg-red-500 px-4 py-2"
                        :href="route('home')"
                        >Go back
                    </Link>
                    <label
                        for="Toggle3"
                        class="inline-flex items-center absolute right-1/2 -translate-y-1 p-2 rounded-md cursor-pointer text-gray-800"
                    >
                        <input
                            id="Toggle3"
                            type="checkbox"
                            class="hidden peer"
                        />
                        <span
                            class="px-4 py-2 rounded-l-md bg-violet-400 peer-checked:bg-gray-300"
                            >Users</span
                        >
                        <span
                            class="px-4 py-2 rounded-r-md bg-gray-300 peer-checked:bg-violet-400"
                            >Groups</span
                        >
                    </label>
                </div>
                <Users :users="users" />
            </div>
            <div
                v-else
                class="flex flex-col gap-4 justify-center items-center h-[80vh]"
            >
                <h1 class="text-xl">No user found...</h1>
                <Link class="rounded bg-red-500 px-4 py-2" :href="route('home')"
                    >Go back
                </Link>
            </div>
        </div>
    </div>
</template>
