<template>
    <div class="modal__content modal__padding-tb">
        <div class="modal__title">Выбрать другой тариф</div>
        <div class="modal-close" v-on:click="$emit('close')"></div>

        <main>
            <vue-loading></vue-loading>
        </main>
    </div>
</template>

<script>
    import VueLoading from 'vue-element-loading';

    export default {
        name: "AnotherTariffModal",
        components: {VueLoading},
        props: ['current-tariff-price', 'number-id', 'tariff-id'],

        data () {
            return {
                loading: true,
                tariffs: [],
            }
        },

        rendered() {
            this.$http.get('tariffs/other', {numberId: this.numberId, tariffId: this.tariffId})
                .then(response => {
                    this.loading = false;
                    this.tariffs = response.body.tariffs;
                });
        }
    }
</script>

<style scoped>

</style>