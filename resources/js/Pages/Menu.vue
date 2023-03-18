<script setup>
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { ref, watch, computed } from "vue";
import debounce from "lodash/debounce";
import { Link, router, usePage } from "@inertiajs/vue3";

let props = defineProps({
    search: String,
});

let filter = ref(props.search);

const friend_requests = computed(() =>
    usePage().props.notifications.filter((n) => n.type.includes("Friend"))
);
const notifications = computed(() =>
    usePage().props.notifications.filter((n) => n.type.includes("User"))
);

const unreadNotifications = computed(
    () =>
        usePage().props.notifications.filter(
            (n) => n.read_at == null && n.type.includes("User")
        ).length
);

watch(
    filter,
    debounce(() => {
        router.get("/search", { search: filter.value });
    }, 300)
);

function acceptRequest(id, notification_id) {
    router.post(
        "/acceptFriend",
        {
            user_id: id,
            notification_id,
        },
        {
            preserveScroll: true,
        }
    );
}

function refuseRequest(id, notification_id) {
    router.post(
        "/refuseFriend",
        {
            user_id: id,
            notification_id,
        },
        {
            preserveScroll: true,
        }
    );
}

function sendMarkNotif() {
    if (unreadNotifications.value > 0) router.post("/notifications/markAsRead");
}

function sendDeleteNotifs() {
    router.delete("notifications/delete");
}
</script>

<template>
    <div
        class="px-5 py-5 flex justify-between items-center bg-white border-b-2 max-h-[9vh]"
    >
        <Link
            :href="route('home')"
            class="font-semibold text-2xl text-blue-600 hover:text-blue-500"
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
            <Dropdown
                :width="
                    friend_requests.length + notifications.length > 0 ? 500 : 48
                "
            >
                <template #trigger>
                    <span class="inline-flex rounded-md mt-2">
                        <button
                            @click="sendMarkNotif"
                            type="button"
                            class="relative"
                        >
                            <unicon
                                width="40"
                                height="40"
                                name="bell"
                                fill="royalblue"
                            />
                            <p
                                class="absolute -top-1 text-md right-[1px] text-white px-1 bg-blue-500 rounded-full"
                            >
                                {{
                                    friend_requests.length + unreadNotifications
                                }}
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
                            :class="{
                                'flex justify-between items-center px-10 py-6 w-full': true,
                                'border-b-2 border-dashed':
                                    friend_requests.length !== index + 1 ||
                                    notifications.length > 0,
                            }"
                        >
                            <p>{{ friend.data.user.name }}</p>
                            <div class="flex gap-4">
                                <button
                                    @click="
                                        acceptRequest(
                                            friend.data.user.id,
                                            friend.id
                                        )
                                    "
                                    class="text-white bg-blue-500 px-2 py-1 rounded hover:bg-blue-600 active:scale-[0.98]"
                                >
                                    Accept
                                </button>
                                <button
                                    @click="
                                        refuseRequest(
                                            friend.data.user.id,
                                            friend.id
                                        )
                                    "
                                    class="text-white bg-red-500 px-2 py-1 rounded hover:bg-red-600 active:scale-[0.98]"
                                >
                                    Decline
                                </button>
                            </div>
                        </div>
                        <div
                            v-for="(notification, index) in $page.props
                                .notifications"
                            :key="index"
                            v-if="notifications.length > 0"
                            :class="{
                                'px-10 py-2 w-full text-center': true,
                                'border-b-2 border-dashed':
                                    notifications.length !== index + 1,
                            }"
                        >
                            {{ notification.data.message }}
                        </div>
                        <div
                            v-if="notifications.length > 0"
                            class="flex justify-center"
                        >
                            <button
                                class="bg-red-500 px-4 py-2 min-w-full text-white"
                                @click="sendDeleteNotifs"
                            >
                                Clear all
                            </button>
                        </div>
                        <div
                            v-else
                            class="p-2 text-center"
                            v-if="friend_requests.length == 0"
                        >
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
