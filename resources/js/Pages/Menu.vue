<script setup>
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { ref, watch, onMounted, inject, provide } from "vue";
import debounce from "lodash/debounce";
import { router } from "@inertiajs/vue3";

let filter = ref();
const filt = inject("filter");
if (filt) filter.value = filt;

watch(
    filter,
    debounce(() => {
        provide("filter", filter.value);
        router.get("/search", { filter: filter.value });
    }, 300)
);
</script>

<template>
    <div
        class="px-5 py-5 flex justify-between items-center bg-white border-b-2"
    >
        <div class="font-semibold text-2xl">GoingChat</div>
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
                            class="h-12 w-12 p-2 bg-yellow-500 rounded-full text-white font-semibold flex items-center justify-center"
                        >
                            {{ $page.props.auth.user.name }}

                            <svg
                                class="ml-2 -mr-0.5 h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
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
