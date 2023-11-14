import { defineStore } from "pinia";

export let useMessagesStore = defineStore("private-messages", {
    state() {
        return {
            messages: {},
            currentChatId: String,
            toUser: Boolean,
            group: String,
            userToAdd: Object,
            userToRemove: Object,
            groupToAdd: Object,
        };
    },
    actions: {
        setMessages(messages) {
            const messArray = Object.values(messages);
            this.messages = messArray;
        },
        addMessage(message) {
            this.messages.push(message);
        },
    },
});
