<script setup>
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { ref, watch } from "vue";
import debounce from "lodash/debounce";
import { Link, router } from "@inertiajs/vue3";

let props = defineProps({
    search: String,
});

let filter = ref(props.search);

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
</script>

<template>
    <div
        class="px-5 py-5 flex justify-between items-center bg-white border-b-2"
    >
        <Link :href="route('home')" class="font-semibold text-2xl"
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
        <div>
            <Dropdown align="right" width="48">
                <template #trigger>
                    <span class="inline-flex rounded-md">
                        <button
                            type="button"
                            class="p-2 bg-yellow-500 rounded-lg text-center text-white font-semibold flex items-center justify-center"
                        >
                            {{ $page.props.auth.user.name }}
                        </button>
                    </span>
                </template>
                <template #content>
                    <DropdownLink :href="route('profile.edit')">
                        Profile
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
