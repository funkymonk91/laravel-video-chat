<template>
    <app-layout>
        <template #header>
            Video Chat
        </template>

        <div class="py-12">
            <h1 class="text-center">Laravel Video Chat</h1>
            <div class="video-container" ref="video-container">
                <video v-for="(video, index) in videoList" :key="index" />
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout';
import Pusher from 'pusher-js';
import Peer from 'peerjs';

export default {
    components: {
        AppLayout
    },
    props: {
        user: Object,
        others: Array
    },
    data() {
        return {
            peers: {}
        };
    },
    mounted() {
        // this.$nextTick(function() {
        this.setupVideoChat();
        // });
    },
    methods: {
        setupVideoChat() {
            // const pusher = this.getPusherInstance();

            const myPeer = new Peer(undefined, {
                host: '/',
                port: '3001'
            });

            const myVideo = document.createElement('video');
            myVideo.muted = true;

            navigator.mediaDevices
                .getUserMedia({
                    video: true,
                    audio: true
                })
                .then(stream => {
                    this.addVideoStream(myVideo, stream);
                });

            // this.channel = pusher.subscribe('video-chat');
            // this.channel.bind(`client-signal-${this.user.id}`, signal => {
            //     const peer = this.getPeer(signal.userId, false);
            //     peer.signal(signal.data);
            // });
        },
        addVideoStream(video, stream) {
            video.srcObject = stream;
            video.addEventListener('loadedmetadata', () => {
                video.play();
            });

            this.$refs['video-container'].append(video);
        },
        getPusherInstance() {
            return new Pusher(process.env.MIX_PUSHER_APP_KEY, {
                authEndpoint: route('VideoChat.auth'),
                cluster: process.env.MIX_PUSHER_APP_CLUSTER,
                auth: {
                    headers: {
                        'X-CSRF-Token': document.head.querySelector(
                            'meta[name="csrf-token"]'
                        ).content
                    }
                }
            });
        }
    }
};
</script>

<style scoped>
.video-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, 300px);
    grid-auto-rows: 300px;
}
video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
