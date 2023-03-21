import { defineStore } from "pinia";

export let useMessagesStore = defineStore("private-messages", {
    state() {
        return {
            messages: [],
            currentChatId: String,
            toUser: Boolean,
        };
    },
    actions: {
        setMessages(messages) {
            this.messages = messages;
        },
        addMessage(message) {
            this.messages.push(message);
        },
    },
});
