<template>
    <div>
        <modal name="callback-popup" height="auto" :width="460">
            <div class="modal__content">
                <div class="modal__title">{{ subject }}</div>
            </div>
        </modal>
    </div>
</template>

<script>
    export default {

        mounted() {
            //console.log('Component mounted.')
        },

        data() {

            return {
                subject: 'Подключить'
            }
        },

        methods: {

            open: function (subject) {
                this.subject = subject ? subject : 'Подключить';
                this.$modal.show('callback-popup');
            }

        }
    }
</script>
