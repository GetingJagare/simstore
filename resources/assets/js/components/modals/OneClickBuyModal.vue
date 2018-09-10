<template>
    <div class="modal__content modal__padding-tb">
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
                            <span class="checkbox__text">Нажимая кнопку, Вы принимаете <a href="/policy">условия</a></span>
                        </label>
                    </div>
                    <div>
                        <button class="button" type="submit">Оформить заказ</button>
                    </div>
                </div>
            </form>

        </div>

    </div>
</template>

<script>
    import InfoPopup from './InfoPopup.vue';
    export default {
        props: ['number'],

        mounted() {

        },

        data() {
            return {
                order: {
                    name: '',
                    phone: '',
                    address: '',
                    policy: false
                }
            }
        },

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

                this.$http.post('/cart/order/one-click', {
                    id: this.number.id,
                    name: this.order.name,
                    phone: this.order.phone,
                    address: this.order.address
                }).then(response => {

                    this.$modal.show(InfoPopup, {
                        title: 'Спасибо за заявку!',
                        text: 'Мы свяжемся с Вами в ближайшее время для уточнения всех деталей'
                    }, {
                        height: 'auto',
                        adaptive: true,
                        width: 400
                    });

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

            }

        }
    }
</script>
