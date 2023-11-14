<script setup>
import { Link } from "@inertiajs/vue3";
let props = defineProps({
    links: Array,
    toggle: Boolean,
});
</script>

<template>
    <div class="flex justify-center">
        <div class="flex gap-4">
            <div v-for="(link, index) in links" :key="index">
                <Link
                    v-if="!link.label.includes('&')"
                    :href="link.url ? link.url : ''"
                    v-html="link.label"
                    :data="{ toggle: toggle }"
                    :class="{
                        'px-4 py-2 rounded ': true,
                        'bg-gray-50 text-black border': !link.active,
                        'bg-orange-500 text-white': link.active,
                    }"
                />
                <Link
                    v-else
                    :href="link.url ? link.url : ''"
                    v-html="link.label"
                    :data="{ toggle: toggle }"
                    :only="['users', 'groups']"
                    :class="{
                        'px-4 py-2 rounded text-white': true,
                        'bg-gray-500': !link.url,
                        'bg-blue-500': link.url,
                    }"
                />
            </div>
        </div>
    </div>
</template>
