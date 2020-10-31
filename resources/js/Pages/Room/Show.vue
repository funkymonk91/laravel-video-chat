<template>
    <app-layout>
        <template #header>
            Video Chat
        </template>

        <div class="py-12">
            <h1 class="text-center">Laravel Video Chat</h1>
            <div class="video-container" ref="video-container">
                <!-- <video v-for="(video, index) in videoList" :key="index" /> -->
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout';
// import Peer from 'peerjs';

export default {
    components: {
        AppLayout
    },
    props: {
        room: Object,
        user: Object
    },
    data() {
        return {
            peers: {}
            // myPeer: new Peer(undefined, {
            //     host: '/',
            //     port: '3001'
            // });
        };
    },
    mounted() {
        // this.setupVideoChat();

        Echo.join(`room.${this.room.id}`)
            .here(users => {
                console.log(users);
            })
            .joining(user => {
                console.log('joined', user);
            })
            .leaving(user => {
                console.log('left', user);
            })
            .listen('NewMessage', e => {
                console.log(e);
            });
    },
    methods: {
        setupVideoChat() {
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
        },
        addVideoStream(video, stream) {
            video.srcObject = stream;
            video.addEventListener('loadedmetadata', () => {
                video.play();
            });

            this.$refs['video-container'].append(video);
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
