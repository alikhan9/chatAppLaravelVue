<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
    users: Array,
});
</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>
        <div v-if="!users" class="h-screen shadow-lg rounded-lg">
            <!-- Chatting -->
            <div class="flex flex-row justify-between bg-white h-full">
                <!-- chat list -->
                <div class="flex flex-col w-2/5 border-r-2 overflow-y-auto">
                    <!-- search compt -->
                    <div class="border-b-2 py-4 px-2">
                        <input
                            type="text"
                            placeholder="search chatting"
                            class="py-2 px-2 border-2 border-gray-200 rounded-2xl w-full"
                        />
                    </div>
                    <!-- end search compt -->
                    <!-- user list -->
                    <div
                        class="flex flex-row py-4 px-2 justify-center items-center border-b-2"
                    >
                        <div class="w-1/4">
                            <img
                                src="https://source.unsplash.com/_7LbC5J-jw4/600x600"
                                class="object-cover h-12 w-12 rounded-full"
                                alt=""
                            />
                        </div>
                        <div class="w-full">
                            <div class="text-lg font-semibold">Luis1994</div>
                            <span class="text-gray-500"
                                >Pick me at 9:00 Am</span
                            >
                        </div>
                    </div>

                    <!-- end user list -->
                </div>
                <!-- end chat list -->
                <!-- message -->
                <div class="w-full px-5 flex flex-col justify-between">
                    <div class="flex flex-col mt-5">
                        <div class="flex justify-end mb-4">
                            <div
                                class="mr-2 py-3 px-4 bg-blue-400 rounded-bl-3xl rounded-tl-3xl rounded-tr-xl text-white"
                            >
                                Welcome to group everyone !
                            </div>
                            <img
                                src="https://source.unsplash.com/vpOeXr5wmR4/600x600"
                                class="object-cover h-8 w-8 rounded-full"
                                alt=""
                            />
                        </div>
                        <div class="flex justify-start mb-4">
                            <img
                                src="https://source.unsplash.com/vpOeXr5wmR4/600x600"
                                class="object-cover h-8 w-8 rounded-full"
                                alt=""
                            />
                            <div
                                class="ml-2 py-3 px-4 bg-gray-400 rounded-br-3xl rounded-tr-3xl rounded-tl-xl text-white"
                            >
                                Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Quaerat at praesentium, aut
                                ullam delectus odio error sit rem. Architecto
                                nulla doloribus laborum illo rem enim dolor odio
                                saepe, consequatur quas?
                            </div>
                        </div>
                    </div>
                    <div class="py-5">
                        <input
                            class="w-full bg-gray-300 py-5 px-3 rounded-xl"
                            type="text"
                            placeholder="type your message here..."
                        />
                    </div>
                </div>
                <!-- end message -->
                <div class="w-2/5 border-l-2 px-5">
                    <div class="flex flex-col">
                        <div class="font-semibold text-xl py-4">
                            Mern Stack Group
                        </div>
                        <img
                            src="https://source.unsplash.com/L2cxSuKWbpo/600x600"
                            class="object-cover rounded-xl h-64"
                            alt=""
                        />
                        <div class="font-semibold py-4">
                            Created 22 Sep 2021
                        </div>
                        <div class="font-light">
                            Lorem ipsum dolor sit amet consectetur adipisicing
                            elit. Deserunt, perspiciatis!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>