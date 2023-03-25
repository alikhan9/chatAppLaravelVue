<script setup>
import { Link, router } from "@inertiajs/vue3";
import Paginate from "@/Pages/Paginate.vue";
import Swal from "sweetalert2";

defineProps({
    groups: Object,
    toggle: Boolean,
});

function sendRequest(id) {
    router.post(
        "/joinGroup",
        { group_id: id },
        {
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
    <div>
        <div class="min-h-[60vh]">
            <div class="grid grid-cols-3 w-[90vw] gap-4">
                <div
                    v-for="group in groups.data"
                    :id="group.id"
                    class="border rounded p-6 shadow-lg mt-5 h-[80px]"
                >
                    <div class="flex justify-between">
                        <Link
                            :href="
                                route('groupProfile', {
                                    id: group.id,
                                })
                            "
                            as="button"
                            >{{ group.name }}
                        </Link>
                        <button
                            @click="sendRequest(group.id)"
                            class="text-blue-500 hover:text-blue-600 active:scale-95 transition duration-200"
                        >
                            Join group
                        </button>
                    </div>
                    <div class="text-sm pb-2 italic">
                        By :
                        <Link
                            :href="
                                route('publicProfile', {
                                    id: group.owner.id,
                                })
                            "
                            class="text-blue-500"
                        >
                            {{ group.owner.name }}
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <Paginate :toggle="toggle" class="mt-20" :links="groups.links" />
    </div>
</template>
