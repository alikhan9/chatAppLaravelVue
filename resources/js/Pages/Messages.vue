<script setup>
import { useMessagesStore } from "@/Stores/useMessagesStore.js";
import { ref, watch, onMounted } from "vue";
import { DateTime } from "luxon";

let props = defineProps({
    id: String,
});

let useMessages = useMessagesStore();
let currentMessage = ref("");
let sending = ref(false);
const container = ref(null);

function sendMessage() {
    if (!sending.value && currentMessage.value != "") {
        sending.value = true;
        const options = {
            url: "/message/private",
            method: "POST",
            data: {
                to: props.id,
                message: currentMessage.value,
            },
        };

        axios(options).then((response) => {
            useMessages.addMessage(response.data);
            currentMessage.value = "";
            scrollToBottom();
            sending.value = false;
        });
    }
}

function convertDateToHuman(date) {
    return DateTime.fromISO(date).toRelative();
}

onMounted(() => {
    scrollToBottom();
});

const scrollToBottom = () => {
    container.value.scrollTop = container.value.scrollHeight;
};

watch(
    useMessagesStore().messages,
    () => {
        setTimeout(() => {
            scrollToBottom();
        }, 1);
    },
    { deep: true }
);
</script>

<template>
    <div class="w-full px-5 flex flex-col justify-between">
        <div class="overflow-auto scrollbar-hide" ref="container">
            <div
                v-for="(message, index) in useMessages.messages"
                :key="index"
                :class="{
                    'grid mb-4 mt-5 items-center gap-1': true,
                    'justify-end': message.from == $page.props.auth.user.id,
                    'justify-start': message.to == $page.props.auth.user.id,
                }"
            >
                <div
                    :class="{
                        'rounded-bl-3xl rounded-tl-3xl py-3 px-4 rounded-tr-xl text-white': true,
                        'mr-2 bg-blue-400 ':
                            message.from == $page.props.auth.user.id,
                        'ml-2 bg-gray-400':
                            message.to == $page.props.auth.user.id,
                    }"
                >
                    {{ message.message }}
                </div>
                <div class="flex justify-end">
                    {{ convertDateToHuman(message.created_at) }}
                </div>
            </div>
        </div>
        <div class="py-5">
            <input
                class="w-full bg-gray-300 py-5 px-3 rounded-xl"
                type="text"
                placeholder="type your message here..."
                v-model="currentMessage"
                @keydown.enter="sendMessage"
                :disable="!sending"
            />
        </div>
    </div>
</template>
