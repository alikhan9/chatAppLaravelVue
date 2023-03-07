<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";
let props = defineProps({
    users: Array,
});
</script>
<template>
    <Head title="Users" />
    <AuthenticatedLayout :filter="filter">
        <div class="flex items-center w-screen justify-center h-[91vh]">
            <div class="w-[800px] min-h-[80vh]">
                <Link class="rounded bg-red-500 px-8 py-2" :href="route('home')"
                    >Back</Link
                >
            </div>
        </div>
    </AuthenticatedLayout>
</template>
