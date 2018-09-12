<template>
    <div class="sidebar-block" v-if="numbers.length > 0">
        <div class="sidebar-block__header">
            <div class="big-h1">Успей купить!</div>
            <b>номер дня со <span>скидкой</span></b>
        </div>
        <div class="sidebar-block__content offers-small">
            <div class="sidebar-countdown text-center">
                <b>Предложение доступно:</b>
                <countdown v-if="end" :end="end"></countdown>
            </div>
            <div class="offers-items">
                <table class="w-100">
                    <tbody>
                    <tr v-for="number in numbers">
                        <td class="offers-item__operator">
                            <span class="beeline"></span>
                        </td>
                        <td>
                            <div class="offers-item__number" style="letter-spacing: -0.8px">{{ number.value }}</div>
                            <div class="d-inline-flex">
                                <div class="offers-item__old-price"><span>{{ number.price_new }} ₽</span></div>
                                <div class="offers-item__price">{{ number.final_price }} ₽</div>
                            </div>
                        </td>
                        <td class="offers-item__buy text-right">
                            <a href="#" v-if="cart.numbers.includes(number.id)" class="offers-item__basket active" v-on:click.prevent="showNumberAlreadyInCartModal">В корзину</a>
                            <a href="#" class="offers-item__basket" v-on:click.prevent="addNumberToCart(number)" v-else>В корзину</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="sidebar-block__bottom-info">
                <p>Еще больше скидок!</p>
                <p>Каждые 3 часа мы отбираем несколько случайных номеров и делаем одно из лучших предложений по цене</p>
            </div>
        </div>
    </div>
</template>

<script>
    import InfoPopup from './modals/InfoPopup.vue';
    export default {

        props: ['cart'],

        created() {
            this.getItems();
        },

        mounted() {


        },

        data() {
            return {
                numbers: [],
                end: ''
            }
        },

        methods: {


            showNumberAlreadyInCartModal() {

                return this.$modal.show(InfoPopup, {
                    title: 'Уже в корзине',
                    text: 'Данный номер ранее уже был добавлен в корзину',
                    link: {name: 'Перейти в корзину', href: '/cart'}
                }, {
                    height: 'auto',
                    adaptive: true,
                    width: 400
                });

            },

            getItems() {

                this.$http.get('/numbers-promo').then(response => {

                    this.numbers = response.body.numbers;
                    this.end = response.body.end;

                }, response => {



                });

            },

            addNumberToCart(item) {

                this.$http.post('/cart/add-number', {id: item.id}).then(response => {

                    //this.numbers = response.body.numbers;

                    this.$modal.show(InfoPopup, {
                        title: 'Добавлено в корзину',
                        text: 'Выбранный номер добавлен в корзину. Вы можете перейти к оформлению заказа или продолжить покупки.',
                        link: {name: 'Перейти в корзину', href: '/cart'}
                    }, {
                        height: 'auto',
                        adaptive: true,
                        width: 400
                    });

                    this.$trigger('updateCart', {numbers: response.body.numbers});
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
