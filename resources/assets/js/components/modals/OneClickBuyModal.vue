<template>
    <div class="modal__content modal__padding-tb">
        <loading :active="loading" color="#f7d23e"></loading>
        <div v-if="!loading">
            <div class="modal__title">Купить в 1 клик</div>
            <div class="modal-close" v-on:click="$emit('close')"></div>
            <main style="padding: 0">
                <div class="offers-small">
                    <div class="offers-items" style="margin-top: 0">
                        <table class="w-100">


                            <tbody>

                            <tr>
                                <td class="offers-item__operator">
                                    <span class="beeline"></span>
                                </td>
                                <td>
                                    <div class="offers-item__number" style="letter-spacing: -0.8px">{{ number.value }}</div>
                                    <div class="d-inline-flex">
                                        <div class="offers-item__old-price" v-if="number.price_new != number.final_price"><span>{{ number.price_new }} ₽</span></div>
                                        <div class="offers-item__price" v-if="number.final_price > 0">{{ number.final_price }} ₽</div>
                                        <div class="offers-item__price" v-else>Договорная</div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 40px;"></td>
                                <td>
                                    <div class="offers-item__tariff">Тариф: {{ tariff.name }}</div>
                                    <div class="d-inline-flex">
                                        <div class="offers-item__old-price" v-if="tariff.price != tariff.final_price"><span>{{ tariff.price }} ₽</span></div>
                                        <div class="offers-item__price" v-if="tariff.final_price > 0">{{ tariff.final_price }} ₽</div>
                                        <div class="offers-item__price" v-else>Договорная</div>
                                    </div>
                                    <a href="#" class="button offers-item__another-tariff"
                                       @click.prevent="chooseAnotherTariff(number.id, tariff.id, tariff.price)">Выбрать
                                        другой тариф</a>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

            <div style="margin: 20px 0 10px 0">

                <div class="order-h1 big-h1">Ваши контакты</div>
                <form @submit.prevent="submit">
                    <div class="order-form">
                        <div class="d-flex flex-wrap flex-lg-nowrap">
                            <div class="order-form__input first">
                                <div class="order-form__label">Ваше имя</div>
                                <input type="text" v-model="order.name" placeholder="Иван">
                            </div>
                            <div class="order-form__input">
                                <div class="order-form__label">Ваш номер телефона</div>
                                <input type="text" v-model="order.phone" placeholder="+7 (999) 999-99-99" v-mask="'+7 (###) ###-##-##'">
                            </div>
                        </div>
                        <div class="order-form__input last">
                            <div class="order-form__label">Адресс доставки</div>
                            <input type="text" v-model="order.address" placeholder="г. Москва, ул. Тверская, д. 30, кв. 5">
                        </div>
                    </div>
                    <div class="d-flex flex-wrap flex-sm-nowrap order-form__bottom">
                        <div class="order-form__checkbox">
                            <label class="checkbox d-flex">
                                <input type="checkbox" v-model="order.policy" />
                                <span class="checkbox__text">Нажимая кнопку, Вы принимаете <a href="/politika-konfidentsialnosti">условия</a></span>
                            </label>
                        </div>
                        <div>
                            <button class="button" type="submit">Оформить заказ</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<script>
    import InfoPopup from './InfoPopup.vue';
    import Loading from 'vue-element-loading';
    import AnotherTariffModal from './AnotherTariffModal';

    export default {
        props: ['number'],

        mounted() {
            this.getTariff();
        },

        data() {
            return {
                loading: true,
                order: {
                    name: '',
                    phone: '',
                    address: '',
                    policy: false
                },
                tariff: {},
            }
        },

        components: {Loading, AnotherTariffModal},

        methods: {

            submit() {

                if(this.order.phone.length < 18) {

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

                if(!this.order.policy) {

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

                var params = Object.assign({}, {
                    id: this.number.id,
                    tariffId: this.tariff.id,
                    name: this.order.name,
                    phone: this.order.phone,
                    address: this.order.address
                }, {utm_tags: getUTMTags()}, {ga: getGACookies()});

                this.$http.post('/cart/order/one-click', params).then(response => {

                    this.$modal.show(InfoPopup, {
                        title: 'Спасибо за заявку!',
                        text: 'Мы свяжемся с Вами в ближайшее время для уточнения всех деталей'
                    }, {
                        height: 'auto',
                        adaptive: true,
                        width: 400
                    });

                    addToDataLayer({'event':'lead_one_click_form'});

                }, response => {

                    alert('Произошла ошибка при загрузке данных');

                });

                /*

                this.$http.post('/crm', {fields: {
                    subject: 'Заказ в один клик',
                    name: this.order.name,
                    phone: this.order.phone,
                    address: this.order.address,
                    number: this.number.value
                }}).then(response => {

                }, response => {

                    alert('Произошла ошибка.')

                });*/

                this.$emit('close');

            },

            getTariff() {
                this.$http.get('/cart/get-number-tariff?id=' + this.number.id)
                    .then(response => {
                        this.tariff = response.body.tariff;
                        this.loading = false;
                    });
            },

            chooseAnotherTariff(numberId, tariffId, currentTariffPrice) {
                this.$modal.show(AnotherTariffModal, {
                    price: currentTariffPrice,
                    nid: numberId,
                    tid: tariffId,
                    oneclick: true,
                    popup: this
                }, {
                    height: 'auto',
                    adaptive: true,
                    width: 700,
                    scrollable: true
                });
            },

        }
    }
</script>
