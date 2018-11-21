<template>
    <div class="modal__content modal__padding-tb">
        <div class="modal__title">{{ title }}</div>
        <div class="modal-close" v-on:click="$emit('close')"></div>
        <div style="margin: 10px 0 0 0">{{ text }}</div>
        <div v-if="link" style="margin: 20px 0 0">
            <a :href="link.href" class="button" v-if="link.constructor.name === 'Object'">{{ link.name }}</a>
            <div v-else-if="link.constructor.name === 'Array'">
                <a v-for="l in link" :href="l.href" class="button button_margin-left" @click="callback($event, l.method)">{{ l.name }}</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['title', 'text', 'link'],

        mounted() {

        },

        methods: {
            callback: function (e, method) {
                if (method) {
                    e.preventDefault();
                    this.$emit(method);
                }
            }
        }
    }
</script>

<style scoped>
    .modal__content .button {
        padding: 0 10px;
        font-size: 14px;
        margin: 8px 8px 0 0;
    }

    .modal__content .button:last-child {
        margin-right: 0;
    }
</style>