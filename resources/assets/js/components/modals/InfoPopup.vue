<template>
    <div class="modal__content modal__padding-tb">
        <div class="modal__title">{{ title }}</div>
        <div class="modal-close" v-on:click="$emit('close')"></div>
        <div style="margin: 10px 0 0 0">{{ text }}</div>
        <div v-if="link" style="margin: 20px 0 0">
            <a :href="link.href" class="button">{{ link.name }}</a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['title', 'text', 'link'],

        mounted() {

        }
    }
</script>
