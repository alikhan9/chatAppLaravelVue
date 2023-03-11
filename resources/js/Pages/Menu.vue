<script setup>
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { ref, watch, inject, onMounted } from "vue";
import debounce from "lodash/debounce";
import { Link, router } from "@inertiajs/vue3";

let props = defineProps({
    search: String,
});

let filter = ref(props.search);
let friend_requests = ref([]);

watch(
    filter,
    debounce(() => {
        router.get(
            "/search",
            { search: filter.value },
            { preserveState: true }
        );
    }, 300)
);

onMounted(() => {
    friend_requests.value = inject("friend_requests");
});

function acceptRequest(id) {
    router.post(
        "/acceptFriend",
        {
            user_id: id,
        },
        {
            preserveScroll: true,
            preserveState: true,
        }
    );
}

function refuseRequest(id) {
    router.post(
        "/refuseFriend",
        {
            user_id: id,
        },
        {
            preserveScroll: true,
            preserveState: true,
        }
    );
}
</script>

<template>
    <div
        class="px-5 py-5 flex justify-between items-center bg-white border-b-2 max-h-[9vh]"
    >
        <Link :href="route('home')" class="font-semibold text-2xl text-blue-600"
            >ChatApp
        </Link>
        <div class="w-1/2">
            <input
                type="text"
                v-model="filter"
                placeholder="search ..."
                class="rounded-2xl bg-gray-100 py-3 px-5 w-full"
            />
        </div>
        <div class="flex gap-4 items-center">
            <Dropdown :width="friend_requests.length > 0 ? 500 : 48">
                <template #trigger>
                    <span class="inline-flex rounded-md">
                        <button
                            type="button"
                            class="px-8 py-4 relative rounded-full text-center bg-blue-500 text-white flex items-center justify-center z"
                        >
                            Notifications
                            <p class="absolute top-2 text-sm right-6">
                                {{ friend_requests.length }}
                            </p>
                        </button>
                    </span>
                </template>
                <template #content>
                    <div class="max-h-[250px] overflow-auto">
                        <div
                            v-for="(friend, index) in friend_requests"
                            v-if="friend_requests.length > 0"
                            :key="index"
                            class="flex justify-between items-center px-10 py-6 w-full border-b-2 border-dashed"
                        >
                            <p>{{ friend.name }}</p>
                            <div class="flex gap-4">
                                <button
                                    @click="acceptRequest(friend.id)"
                                    class="text-white bg-blue-500 px-2 py-1 rounded hover:bg-blue-600 active:scale-[0.98]"
                                >
                                    Accept
                                </button>
                                <button
                                    @click="refuseRequest(friend.id)"
                                    class="text-white bg-red-500 px-2 py-1 rounded hover:bg-red-600 active:scale-[0.98]"
                                >
                                    Decline
                                </button>
                            </div>
                        </div>
                        <div v-else class="p-2 text-center">
                            No notification...
                        </div>
                    </div>
                </template>
            </Dropdown>
            <Dropdown align="right" width="48">
                <template #trigger>
                    <span class="inline-flex rounded-md">
                        <button
                            type="button"
                            class="w-16 h-16 bg-yellow-500 rounded-full text-center text-white font-semibold flex items-center justify-center"
                        >
                            <span class="truncate max-w-[50px]">{{
                                $page.props.auth.user.name
                            }}</span>
                        </button>
                    </span>
                </template>
                <template #content>
                    <DropdownLink :href="route('user.profile')">
                        Profile
                    </DropdownLink>
                    <DropdownLink :href="route('profile.edit')">
                        Settings
                    </DropdownLink>
                    <DropdownLink
                        :href="route('logout')"
                        method="post"
                        as="button"
                    >
                        Log Out
                    </DropdownLink>
                </template>
            </Dropdown>
        </div>
    </div>
</template>
