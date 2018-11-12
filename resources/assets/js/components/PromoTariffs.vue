<template>
    <div class="sidebar-block__content offers-small tariffs">
        <div class="sidebar-countdown text-center">
            <b>Предложение доступно:</b>
            <countdown v-if="end" :end="end"></countdown>
        </div>
        <div class="offers-items">
            <table class="w-100">
                <tbody>
                <tr>
                    <td class="offers-item__operator">
                        <span class="beeline"></span>
                    </td>
                    <td>
                        <div class="offers-item__number">{{ tariff.name }}</div>
                        <div class="offers-item__text">
                            {{ tariff.minutes }} мин. по России<br/>{{ tariff.sms }} SMS/сутки по России<br/>{{ tariff.traffic }} ГБ интернета
                                            </div>
                        <div class="d-inline-flex">
                            <div class="offers-item__old-price"><span>{{ tariff.price }} ₽</span></div>
                            <div class="offers-item__price">{{ tariff.final_price }} ₽</div>
                        </div>
                    </td>
                    <td class="offers-item__buy text-right">
                        <a href="#" v-if="cart.tariffs.includes(tariff.id)" class="offers-item__basket active" v-on:click.prevent="showNumberAlreadyInCartModal">В корзину</a>
                        <a href="#" class="offers-item__basket" v-on:click.prevent="addTariffToCart(tariff)" v-else>В корзину</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>

<script>
    import InfoPopup from './modals/InfoPopup.vue';
    export default {

        props: ['cart'],

        mounted() {

            this.getItems();

        },

        data() {
            return {
                tariff: {},
                end: ''
            }
        },

        methods: {

            showNumberAlreadyInCartModal() {

                return this.$modal.show(InfoPopup, {
                    title: 'Уже в корзине',
                    text: 'Данный тариф ранее уже был добавлен в корзину',
                    link: {name: 'Перейти в корзину', href: '/korzina'}
                }, {
                    height: 'auto',
                    adaptive: true,
                    width: 400
                });

            },

            getItems() {

                this.$http.get('/tariffs-promo').then(response => {

                    this.tariff = response.body.tariff;
                    this.end = response.body.end;

                }, response => {



                });

            },

            addTariffToCart(item) {


                this.$http.post('/cart/add-tariff', {id: item.id}).then(response => {

                    //this.numbers = response.body.numbers;

                    this.$modal.show(InfoPopup, {
                        title: 'Добавлено в корзину',
                        text: 'Выбранный тариф добавлен в корзину. Вы можете перейти к оформлению заказа или продолжить покупки.',
                        link: {name: 'Перейти в корзину', href: '/korzina'}
                    }, {
                        height: 'auto',
                        adaptive: true,
                        width: 400
                    });

                    this.$trigger('updateCart', {tariffs: response.body.tariffs});
                    document.getElementsByClassName('basket-count')[0].innerHTML = response.body.count;
                    document.getElementsByClassName('basket-mobile-count')[0].innerHTML =  response.body.count;

                    addToDataLayer({'event':'basket_add'});

                }, response => {

                    alert('Произошла ошибка при загрузке данных');

                });

            },

        }
    }
</script>
