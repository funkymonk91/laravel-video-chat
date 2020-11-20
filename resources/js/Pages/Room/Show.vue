<template>
    <app-layout>
        <template #header>
            Video Chat
        </template>

        <div class="py-12">
            <h1 class="text-center">Laravel Video Chat</h1>
            <div class="video-container" ref="video-container"></div>
            <text-chat :user="user" :room="room"></text-chat>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout';
import TextChat from './Components/TextChat';
import Peer from 'peerjs';

export default {
    components: {
        AppLayout,
        TextChat
    },
    props: {
        room: Object,
        user: Object
    },
    data() {
        return {
            peers: {},
            myPeerId: `${this.room.id}-${this.user.id}`,
            presenceChannel: Echo.join(`room.${this.room.id}`),
            myPeer: new Peer()
        };
    },
    mounted() {
        this.setupVideoChat();

        this.presenceChannel
            .here(users => {
                console.log('room users ', users);
            })
            .leaving(user => {
                console.log('user leaving ', user);
                if (this.peers[this.myPeerId]) {
                    this.peers[this.myPeerId].close();
                }
            });
    },
    methods: {
        setupVideoChat() {
            const self = this;
            const myVideo = document.createElement('video');
            myVideo.muted = true;

            navigator.mediaDevices
                .getUserMedia({
                    video: true,
                    audio: true
                })
                .then(function(stream) {
                    self.addVideoStream(myVideo, stream);

                    self.presenceChannel.joining(function(user) {
                        console.log('user joining ', user);
                        self.connectToNewUser(user, stream);
                    });

                    self.myPeer.on('call', function(call) {
                        console.log('hi');
                        call.answer(stream);
                        const video = document.createElement('video');

                        call.on('stream', function(userVideoStream) {
                            console.log('hi', userVideoStream);
                            self.addVideoStream(video, userVideoStream);
                        });
                    });
                });
        },
        addVideoStream(video, stream) {
            video.srcObject = stream;
            video.addEventListener('loadedmetadata', function() {
                video.play();
            });

            this.$refs['video-container'].append(video);
        },
        connectToNewUser(user, stream) {
            const userPeerId = `${this.room.id}-${user.id}`;
            const call = this.myPeer.call(userPeerId, stream);
            const video = document.createElement('video');

            call.on('stream', function(userVideoStream) {
                this.addVideoStream(video, userVideoStream);
            });

            call.on('close', function() {
                video.remove();
            });

            this.peers[userPeerId] = call;
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
