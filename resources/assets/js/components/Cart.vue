<template>
    <div class="main-wrap d-flex flex-wrap flex-lg-nowrap justify-content-between">
        <div class="cart-main cart">
            <div class="big-h1">Моя корзина</div>
            <div class="cart-main__box">

                <div class="cart-items">
                    <div class="offers-items" v-if="numbers.length > 0">
                        <div class="cart-items__item d-flex flex-wrap" v-for="number in numbers">
                            <div class="cart-items__item-col">
                                <div class="cart__label w-100">Номер:</div>
                                <div class="cart-items__item-number">
                                    <span class="beeline"></span> {{ number.value }}
                                </div>
                                <div class="cart-items__item-number-params w-100 d-flex align-items-center d-xl-none">
                                    <div class="cart-items__item-price" v-if="number.final_price > 0">
                                        {{ number.final_price }} ₽
                                    </div>
                                    <div class="cart-items__item-price" v-else>
                                        Договорная
                                    </div>
                                    <div class="cart-items__item-old-price">
                                        <span v-if="number.price != number.final_price">{{ number.price }} ₽</span>
                                    </div>
                                    <div class="cart-items__item-buy text-right">
                                        <a href="#" class="cart-items__remove"
                                           v-on:click.prevent="remove('number', number.id)">Удалить</a>
                                    </div>
                                </div>
                                <div class="cart-items__item-tariff w-100" v-if="number.tariff">
                                    <div class="cart__label w-100">Тариф:</div>
                                    <div class="cart-items__item-tariff-name">
                                        {{ number.tariff.name }}&nbsp;
                                        <a href="#" class="button cart-items__another-tariff"
                                           @click.prevent="chooseAnotherTariff(number.id, number.tariff.id, number.tariff.price)">Выбрать
                                            другой тариф</a>
                                    </div>
                                    <div class="cart-items__item-tariff-params w-100 d-flex align-items-center d-xl-none"
                                         v-if="number.tariff">
                                        <div class="cart-items__item-price" v-if="number.final_price > 0">
                                            {{ number.tariff.final_price !== number.tariff.price ? number.tariff.final_price
                                            : number.tariff.price }} ₽
                                            &nbsp;
                                        </div>
                                        <div class="cart-items__item-old-price"
                                             v-if="number.tariff.final_price !== number.tariff.price">
                                            <span>{{ number.tariff.price }} ₽</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cart-items__item-col flex-grow-1 d-flex flex-wrap">
                                <div class="w-100 d-xl-flex cart-items__item-number-params align-items-center">
                                    <div class="cart-items__item-price" v-if="number.final_price > 0">
                                        {{ number.final_price }} ₽
                                    </div>
                                    <div class="cart-items__item-price" v-else>
                                        Договорная
                                    </div>
                                    <div class="cart-items__item-old-price">
                                        <span v-if="number.price != number.final_price">{{ number.price }} ₽</span>
                                    </div>
                                    <div class="cart-items__item-buy text-right">
                                        <a href="#" class="cart-items__remove"
                                           v-on:click.prevent="remove('number', number.id)">Удалить</a>
                                    </div>
                                </div>
                                <div class="w-100 d-xl-flex cart-items__item-tariff-params align-items-center"
                                     v-if="number.tariff">
                                    <div class="cart-items__item-price">
                                        {{ number.tariff.final_price !== number.tariff.price ? number.tariff.final_price
                                        : number.tariff.price }} ₽
                                        &nbsp;
                                    </div>
                                    <div class="cart-items__item-old-price"
                                         v-if="number.tariff.final_price !== number.tariff.price">
                                        <span>{{ number.tariff.price }} ₽</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="/" v-else class="button">Выбрать номер</a>
                </div>
                <!--<div class="cart__label">Тариф:</div>
                <div class="cart-items">
                    <div class="offers-items" v-if="tariffs.length > 0">
                        <table class="w-100">
                            <tbody>

                            <tr v-for="tariff in tariffs">
                                <td class="offers-item__operator">
                                    <span class="beeline"></span>
                                </td>
                                <td class="offers-item__number">
                                    {{ tariff.name }}
                    </td>
                                <td class="offers-item__price text-center" v-if="tariff.price > 0">
                                    {{ tariff.final_price }} ₽
                        </td>
                                <td class="offers-item__price text-center" v-else>
                                    Договорная
                                </td>
                                <td class="offers-item__old-price text-center">
                                    <span v-if="tariff.price != tariff.final_price">{{ tariff.price }} ₽</span>
                                </td>
                                <td class="offers-item__buy text-right">
                                    <a href="#" class="cart-items__remove" v-on:click.prevent="remove('tariff', tariff.id)">Удалить</a>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                    <a href="/tarify" v-else class="button">Выбрать тариф</a>
                </div>-->
                <div class="text-sm-right cart__total">Сумма заказа: <b>{{ price }} ₽</b></div>

            </div>
        </div>

        <div class="cart-order">
            <div class="big-h1">Оформить заказ</div>
            <div class="cart-order__box">

                <div class="order-h1 big-h1">Ваши контакты</div>
                <form @submit.prevent="order">
                    <div class="order-form">
                        <div class="d-flex flex-wrap flex-xl-nowrap">
                            <div class="order-form__input first">
                                <div class="order-form__label">Ваше имя</div>
                                <input type="text" v-model="form.name" placeholder="Иван">
                            </div>
                            <div class="order-form__input">
                                <div class="order-form__label">Ваш номер телефона</div>
                                <input type="text" v-model="form.phone" placeholder="+7 (999) 999-99-99"
                                       v-mask="'+7 (###) ###-##-##'">
                            </div>
                        </div>
                        <div class="order-form__input last">
                            <div class="order-form__label">Адресс доставки</div>
                            <input type="text" v-model="form.address"
                                   placeholder="г. Москва, ул. Тверская, д. 30, кв. 5">
                        </div>
                    </div>
                    <div class="d-flex flex-wrap flex-md-nowrap order-form__bottom">
                        <div class="order-form__checkbox">
                            <label class="checkbox d-flex">
                                <input type="checkbox" v-model="policy"/>
                                <span class="checkbox__text">Нажимая кнопку, Вы принимаете <a
                                        href="/politika-konfidentsialnosti">условия</a></span>
                            </label>
                        </div>
                        <div>
                            <button class="button" :disabled="numbers.length == 0 && tariffs.length == 0" type="submit">
                                Оформить заказ
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<script>
    import InfoPopup from './modals/InfoPopup.vue';
    import AnotherTariffModal from './modals/AnotherTariffModal';
    import VueTheMask from 'vue-the-mask'

    Vue.use(VueTheMask);

    export default {
        components: {AnotherTariffModal},
        mounted() {

            //console.log(window.app.__vue__.$refs.ordering);

            this.getItems();
        },

        data() {
            return {
                numbers: [],
                tariffs: [],
                price: 0,
                disabled: true,
                policy: false,
                form: {
                    name: '',
                    phone: '',
                    address: ''
                }
            }
        },

        methods: {

            getItems() {

                this.$http.post('/cart').then(response => {

                    this.numbers = response.body.numbers;
                    //this.tariffs = response.body.tariffs;
                    this.price = response.body.price;

                }, response => {

                    alert('Произошла ошибка при загрузке данных');

                });

            },

            remove(type, id) {

                this.$http.post('/cart/remove', {type: type, id: id}).then(response => {

                    this.numbers = response.body.numbers;
                    //this.tariffs = response.body.tariffs;
                    this.price = response.body.price;

                    document.getElementsByClassName('basket-count')[0].innerHTML = response.body.count;
                    document.getElementsByClassName('basket-mobile-count')[0].innerHTML = response.body.count;

                }, response => {

                    alert('Произошла ошибка при загрузке данных');

                });

            },

            order() {


                if (this.form.phone.length < 18) {

                    this.$modal.show(InfoPopup, {
                        title: 'Ошибка!',
                        text: 'Проверьте корректность введенного номера телефона',
                    }, {
                        height: 'auto',
                        adaptive: true,
                        width: 400
                    });

                    return false;
                }

                if (!this.policy) {

                    this.$modal.show(InfoPopup, {
                        title: 'Ошибка!',
                        text: 'Необходимо принять условия перед оформлением заказа.',
                    }, {
                        height: 'auto',
                        adaptive: true,
                        width: 400
                    });

                    return false;

                }

                var params = Object.assign({}, this.form, {utm_tags: getUTMTags()});

                this.$http.post('/cart/order', params).then(response => {

                    this.numbers = response.body.numbers;
                    //this.tariffs = response.body.tariffs;
                    this.price = response.body.price;

                    this.$modal.show(InfoPopup, {
                        title: 'Заказ успешно оформлен',
                        text: 'Мы свяжемся с вами в ближайшее время.',
                    }, {
                        height: 'auto',
                        adaptive: true,
                        width: 400
                    });

                    this.form = {
                        name: '',
                        phone: '',
                        address: ''
                    };

                    this.policy = false;

                    document.querySelector('.nav-basket').querySelector('.basket-count').innerText = response.body.count;

                    addToDataLayer({'event': 'lead_make_order_form'});

                }, response => {

                    alert('Произошла ошибка при загрузке данных');

                });

            },

            chooseAnotherTariff(numberId, tariffId, currentTariffPrice) {
                this.$modal.show(AnotherTariffModal, {
                    price: currentTariffPrice,
                    nid: numberId,
                    tid: tariffId,
                    oneclick: false,
                }, {
                    height: 'auto',
                    adaptive: true,
                    width: 700,
                    scrollable: true
                });
            },

            recalcPrice: function () {
                let newPrice = 0;
                this.numbers.forEach(number => {
                    newPrice += number.final_price;
                    if (number.tariff) {
                        newPrice += number.tariff.final_price;
                    }
                });
                this.price = newPrice;
            }

        }
    }
</script>