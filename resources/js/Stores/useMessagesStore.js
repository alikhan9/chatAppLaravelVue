import { defineStore } from "pinia";

export let useMessagesStore = defineStore("private-messages", {
    state() {
        return {
            messages: [],
            currentChatId: String,
            toUser: Boolean,
            group: String,
            userToAdd: Object,
            userToRemove: Object,
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
