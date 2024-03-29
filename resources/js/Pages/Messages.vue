<script setup>
import { useMessagesStore } from "@/Stores/useMessagesStore.js";
import { ref, watch, onMounted } from "vue";
import Swal from "sweetalert2";
import { DateTime } from "luxon";

let props = defineProps({
    id: String,
});

let useMessages = useMessagesStore();
let currentMessage = ref("");
let sending = ref(false);
const container = ref(null);

function sendMessage() {
    if (props.id == null) {
        Swal.fire({
            position: "center",
            width: 400,
            icon: "error",
            text: "First select a group or a user to talk to.",
            timer: 4000,
            showConfirmButton: false,
        });
        return;
    }
    if (!sending.value && currentMessage.value != "" && props.id !== null) {
        sending.value = true;
        const options = {
            url: useMessages.toUser ? "/message/private" : "/message/public",
            method: "POST",
            data: {
                to: props.id,
                message: currentMessage.value,
            },
        };

        axios(options).then((response) => {
            const receivedMessage= response.data;
            useMessages.addMessage(receivedMessage);
            currentMessage.value = "";
            sending.value = false;
        });
    }
}



onMounted(() => {
    scrollToBottom();
});

const scrollToBottom = () => {
    container.value.scrollTop = container.value.scrollHeight;
};

watch(
    () => useMessagesStore().messages,
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
            <div v-for="(message, index) in useMessages.messages" :key="index" :class="{
                'grid mb-4 mt-5 items-center gap-1': true,
                'justify-end': message.from == $page.props.auth.user.id,
                'justify-start':
                    message.to == $page.props.auth.user.id ||
                    (!useMessages.toUser &&
                        message.from != $page.props.auth.user.id),
            }">
                <div :class="{
                    'rounded-bl-3xl rounded-tl-3xl py-3 px-4 rounded-tr-xl text-white': true,
                    'mr-2 bg-blue-400 ':
                        message.from == $page.props.auth.user.id,
                    'ml-2 bg-gray-400':
                        message.to == $page.props.auth.user.id ||
                        (!useMessages.toUser &&
                            message.from != $page.props.auth.user.id),
                }">
                    {{
                        useMessages.toUser
                        ? message.message
                        : message.from != $page.props.auth.user.id
                            ? message?.user?.name + " : " + message.message
                            : message.message
                    }}
                </div>
                <div :class="{
                                'text-sm px-4': true,
                                'flex justify-end':
                                    message.from == $page.props.auth.user.id,
                                'justify-start':
                                    message.from != $page.props.auth.user.id,
                            }">
                    {{ message.created_at_human }}
                </div>
            </div>
        </div>
        <div class="py-5">
            <input name="message" class="w-full bg-gray-300 py-5 px-3 rounded-xl" type="text"
                placeholder="type your message here..." v-model="currentMessage" @keydown.enter="sendMessage"
                :disable="!sending" />
        </div>
    </div>
</template>
