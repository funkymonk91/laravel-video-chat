<template>
    <div>
        <div class="max-h-80 h-80 scrolling-auto">
            <div
                v-for="(message, i) in messages"
                :key="i"
                class="bg-gray-700 rounded w-1/2 mb-3"
            >
                <span class="text-gray-300">{{ message.user.name }}</span>
                <div class="text-gray-100">{{ message.message }}</div>
            </div>
        </div>
        <div>
            <span class="text-gray-400" v-if="userTyping"
                >{{ userTyping.name }} is typing...</span
            >
            <div>
                <input type="text" v-model="form.message" @keydown="typing" />
                <button @click="sendMessage">
                    Send
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        user: Object,
        room: Object
    },
    data() {
        return {
            userTyping: null,
            messages: [],
            form: {
                message: ''
            }
        };
    },
    methods: {
        sendMessage() {
            if (this.form.message) {
                axios
                    .post(this.$route('room.chat.store', this.room), {
                        message: this.form.message
                    })
                    .then(e => {
                        console.log(e);
                    });

                this.messages.push({
                    user: this.user,
                    message: this.form.message
                });
                this.form.message = '';
            }
        },
        typing: _.throttle(function() {
            Echo.private(`room.${this.room.id}`).whisper('typing', {
                user: this.user
            });
        }, 300)
    },
    mounted() {
        Echo.join(`room.${this.room.id}`).listen('RoomMessage', e => {
            this.messages.push(e);
        });

        Echo.private(`room.${this.room.id}`).listenForWhisper('typing', e => {
            this.userTyping = e.user;

            setTimeout(() => {
                this.userTyping = null;
            }, 600);
        });
    }
};
</script>

<style></style>
