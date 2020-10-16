<template>
    <app-layout>
        <template #header>
            Video Chat
        </template>

        <div class="py-12">
            <h1 class="text-center">Laravel Video Chat</h1>
            <div class="video-container" ref="video-container"></div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout';
import Video from './Components/Video';
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
            videos: []
        };
    },
    mounted() {
        this.setupVideoChat();
    },
    methods: {
        setupVideoChat() {
            // To show pusher errors
            // Pusher.logToConsole = true;
            const stream = navigator.mediaDevices.getUserMedia({
                video: true,
                audio: true
            });

            const myVideo = new video();
            myVideo.muted = true;

            navigator.mediaDevices
                .getUserMedia({
                    video: true,
                    audio: true
                })
                .then(stream => {
                    this.addVideoStream(myVideo, stream);
                });

            // const pusher = this.getPusherInstance();
            // this.channel = pusher.subscribe('presence-video-chat');
            // this.channel.bind(`client-signal-${this.user.id}`, signal => {
            //     const peer = this.getPeer(signal.userId, false);
            //     peer.signal(signal.data);
            // });
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
        },
        addVideoStream(video, stream) {
            video.srcObject = stream;
            video.addEventListener('loadedmetadata', () => {
                this.$refs['video-container'].$createElement('video');
                video.play();
            });
        }
    }
};
</script>

<style>
/* .video-container {
    width: 500px;
    height: 380px;
    margin: 8px auto;
    border: 3px solid #000;
    position: relative;
    box-shadow: 1px 1px 1px #9e9e9e;
}
.video-here {
    width: 130px;
    position: absolute;
    left: 10px;
    bottom: 16px;
    border: 1px solid #000;
    border-radius: 2px;
    z-index: 2;
}
.video-there {
    width: 100%;
    height: 100%;
    z-index: 1;
}
.text-right {
    text-align: right;
} */
</style>
