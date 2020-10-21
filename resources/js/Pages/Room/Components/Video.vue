<template>
    <video :srcObject="srcObject" :autoplay="autoplay"></video>
</template>

<script>
export default {
    props: ['srcObject', 'autoplay'],
    data: function() {
        return {};
    },
    mounted() {}
};
</script>

<style></style>
