<template>
    <div class="modal__content modal__padding-tb" @opened="getOtherTariffs">
        <div class="modal__title">Выбрать другой тариф</div>
        <div class="modal-close" v-on:click="$emit('close')"></div>

        <main>
            <vue-loading :active="loading || changingTariff" color="#f7d23e"></vue-loading>
            <div class="col-lg-4 modal__tariff" v-for="tariff in tariffs" v-if="tariffs.length">
                <span class="modal__tariff-title"><b>{{ tariff.name}}</b></span>
                <div class="modal__tariff-content">
                    <div v-if="tariff.minutes.length">{{ tariff.minutes + ' мин.' }}</div>
                    <div v-if="tariff.sms.length">{{ tariff.sms + ' SMS' }}</div>
                    <div v-if="tariff.traffic.length">{{ tariff.traffic + ' Гб' }}</div>
                    <div class="modal__tariff-price">
                        <b>{{ tariff.final_price !== tariff.price ? tariff.final_price : tariff.price }}&nbsp;₽</b>
                        <span class="modal__tariff-old-price" v-if="tariff.final_price !== tariff.price">
                            {{ tariff.price }} ₽
                        </span>
                    </div>
                </div>
                <a href="#" class="button modal__tariff-choose-btn" @click.prevent="chooseAnotherTariff(tariff)">Выбрать</a>
            </div>
            <span v-if="!tariffs.length">Для данного номера нет других предложений.</span>
        </main>
    </div>
</template>

<script>
    import VueLoading from 'vue-element-loading';

    export default {
        name: "AnotherTariffModal",
        components: {VueLoading},
        props: ['numbers', 'nid', 'tid'],

        data() {
            return {
                loading: true,
                changingTariff: false,
                tariffs: [],
            }
        },

        mounted() {
            this.getOtherTariffs();
        },

        methods: {
            getOtherTariffs() {
                this.$http.get('tariffs/other?numberId=' + this.nid + '&tariffId=' + this.tid)
                    .then(response => {
                        this.loading = false;
                        this.tariffs = response.body.tariffs;
                    });
            },

            chooseAnotherTariff(tariff) {
                this.changingTariff = true;

                this.$http.post('cart/change-tariff', {numberId: this.nid, tariffId: tariff.id})
                    .then(response => {
                        if (response.body.success) {
                            this.changingTariff = false;
                            let $cart = this.$root.$children[1];
                            $cart.numbers.forEach(number => {
                                if (number.id === this.nid) {
                                    number.tariff = tariff;
                                }
                            });

                            $cart.recalcPrice();
                            this.$emit('close');
                        }
                    });
            }
        },
    }
</script>

<style scoped>
    .modal__tariff {
        display: inline-block;
    }

    .modal__tariff-title {
        margin-bottom: 10px;
        display: inline-block;
    }

    .modal__tariff-content {
        margin-bottom: 20px;
    }

    .modal__content main {
        padding: 50px 0 20px;
    }

    .modal__tariff-choose-btn {
        line-height: 24px;
        height: auto;
        padding: 0 10px;
    }

    .modal__tariff-price {
        margin-top: 30px;
    }

    .modal__tariff-old-price {
        position: relative;
    }

    .modal__tariff-old-price:before {
        content: '';
        display: inline-block;
        width: 100%;
        height: 2px;
        position: absolute;
        background: #f7d23e;
        left: 0;
        top: 8px;
    }
</style>