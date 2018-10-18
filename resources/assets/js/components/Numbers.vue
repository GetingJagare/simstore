<template>
    <div>
        <div class="main-content__header d-flex flex-wrap flex-sm-nowrap justify-content-between">
            <div class="d-flex flex-wrap flex-sm-nowrap align-items-center">
                <div class="main-content__select-count">Выводить на странице:</div>
                <multiselect v-model="form.perpage" @select="perpage" :options="perpage_options" track-by="name" label="name" :allow-empty="false" :searchable="false" :close-on-select="true" :show-labels="false">
                    <template slot="singleLabel" slot-scope="{ option }">{{ option.name }}</template>
                </multiselect>
            </div>

        </div>

        <form @submit.prevent="search">
            <div class="filter-block">
                <div class="filter-block__template d-flex flex-wrap flex-xl-nowrap">
                    <div class="filter-block__template-input">
                        <input type="text" placeholder="Введите желаемые цифры" v-model="form.search">
                    </div>
                    <div class="filter-block__template-info">
                        Вы можете использовать шаблоны, например: <span>
                    <span v-on:click="setPattern('AAAA')">AAAA</span>,
                    <span v-on:click="setPattern('AABB')">AABB</span>,
                    <span v-on:click="setPattern('ABAB')">ABAB</span>,
                    <span v-on:click="setPattern('AABBCC')">AABBCC</span>,
                    <span v-on:click="setPattern('ABCABC')">ABCABC</span>,
                    <span v-on:click="setPattern('ABABAB')">ABABAB</span>
                </span>
                    </div>
                </div>
                <div class="filter-block__checkboxes">
                    <label class="checkbox">
                        <input type="checkbox" v-model="form.position_start" />
                        <span class="checkbox__text">В начале</span>
                    </label>
                    <label class="checkbox">
                        <input type="checkbox" v-model="form.position_middle" />
                        <span class="checkbox__text">В середине</span>
                    </label>
                    <label class="checkbox">
                        <input type="checkbox" v-model="form.position_end" />
                        <span class="checkbox__text">В конце</span>
                    </label>
                    <label class="checkbox">
                        <input type="checkbox" v-model="form.promo" />
                        <span class="checkbox__text">Акция</span>
                    </label>
                    <label class="checkbox">
                        <input type="checkbox" v-model="form.similar" />
                        <span class="checkbox__text">Похожие</span>
                    </label>
                    <label class="checkbox">
                        <input type="checkbox" v-model="form.birth" />
                        <span class="checkbox__text">Год рождения</span>
                    </label>
                    <!--<label class="checkbox">
                        <input type="checkbox" v-model="form.pair" />
                        <span class="checkbox__text">Парные</span>
                    </label>
                    <label class="checkbox">
                        <input type="checkbox" />
                        <span class="checkbox__text">Год рождения</span>
                    </label>-->
                </div>

                <div class="d-flex flex-wrap">
                    <div class="filter-block__price">

                        <div class="d-flex align-items-center">
                            <span>Стоимость:</span>
                            <input type="text" placeholder="от" v-model="form.price[0]">
                            <span>–</span>
                            <input type="text" placeholder="до" v-model="form.price[1]">
                        </div>

                        <div class="filter-block__range">
                            <vue-slider ref="priceSlider" :speed="0" v-model="form.price" :min="0" :max="this.price_max" :process-dragable="true" :tooltip="false" :process-style="processStyle" @drag-end="dataLayerFunction"></vue-slider>
                        </div>
                    </div>
                </div>

                <div class="filter-block__buttons">
                    <button class="filter-block__buttons-search" type="submit">Найти</button>
                    <button class="filter-block__buttons-order no-bg" v-on:click.prevent="openNumberOrderPopup">Номер под заказ</button>
                </div>
            </div>
        </form>


        <div class="offers-block">
            <div class="offers-block__header d-flex flex-wrap flex-xl-nowrap justify-content-between align-items-center">

                <div class="d-flex flex-wrap flex-sm-nowrap align-items-center">
                    <div class="main-content__select-count">Сортировать по:</div>
                    <multiselect @select="sort" v-model="form.sort" :options="sort_options" track-by="name" label="name" :allow-empty="false" :searchable="false" :close-on-select="true" :show-labels="false">
                        <template slot="singleLabel" slot-scope="{ option }">{{ option.name }}</template>
                    </multiselect>
                </div>

                <div class="w-xs-100 offer-help">
                    <a href="#" class="button no-bg" v-on:click.prevent="openModal('Помогите с выбором', 'lead_upper_popup_form')">
                        <span class="d-inline d-lg-none d-xl-inline">Помогите с выбором</span>
                        <span class="d-none d-lg-inline d-xl-none">Помощь</span>
                    </a>
                </div>
            </div>
            <div class="offers-items" v-if="numbers.length > 0">
                <table class="w-100">
                    <thead>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-center"><b>Цена</b></td>
                        <td></td>
                        <td class="text-center"><b>Аренда</b></td>
                    </tr>
                    </thead>

                    <tbody>

                    <tr v-for="number in numbers">
                        <td class="offers-item__operator">
                            <span class="beeline"></span>
                        </td>
                        <td class="offers-item__number">
                            {{ number.value }}
                        </td>
                        <td class="offers-item__price text-center" v-if="number.price_new > 0">
                            {{ number.final_price }} ₽
                        </td>
                        <td class="offers-item__price text-center" v-else>
                            Договорная
                        </td>
                        <td class="offers-item__old-price text-center">
                            <span v-if="number.price_new != number.final_price">{{ number.price_new }} ₽</span>
                        </td>
                        <td class="offers-item__price text-center" v-if="number.price_rental > 0">
                            {{ number.price_rental }} ₽/мес <span class="offers-item__price__rental" v-tooltip="'Вы можете арендовать номер вместо его покупки. Стоимость аренды включена в тариф.'">?</span>
                        </td>
                        <td class="offers-item__price text-center" v-else>
                            Договорная <span class="offers-item__price__rental" v-tooltip="'Вы можете арендовать номер вместо его покупки. Стоимость аренды включена в тариф.'">?</span>
                        </td>
                        <td class="offers-item__buy text-right">
                            <a href="#" class="offers-item__one-click button" v-on:click.prevent="oneClick(number)">Купить в 1 клик</a>
                            <a href="#" v-if="cart.numbers.includes(number.id)" class="offers-item__basket active" v-on:click.prevent="showNumberAlreadyInCartModal">В корзину</a>
                            <a href="#" class="offers-item__basket"
                               v-on:click.prevent="addNumberToCart(number)" v-else>В корзину</a>
                        </td>
                    </tr>

                    </tbody>
                </table>
                <div class="offers-more text-center" v-if="numbers_count > this.numbers.length">
                    <a href="#" class="button no-bg" v-on:click.prevent="nextNumbers">Показать ещё</a>
                </div>
            </div>
            <div v-else style="margin: 20px 0 0">
                Номеров по заданным параметрам не найдено.
            </div>
        </div>

        <!--<modal name="one-click" height="auto" :width="460" :scrollable="true" :adaptive="true">
            <div class="modal__content modal__padding-tb">
                <div class="modal__title">Купить в 1 клик</div>
                <div class="modal-close" v-on:click="$modal.hide('one-click')"></div>
                <div class="offers-items" style="margin-top: 0">
                    <table class="w-100">
                        <tbody>

                        <tr>
                            <td class="offers-item__operator">
                                <span class="beeline"></span>
                            </td>
                            <td class="offers-item__number">
                                {{ number.value }}
                        </td>
                            <td class="offers-item__price text-center" v-if="number.price > 0">
                                {{ number.final_price }} ₽
                        </td>
                            <td class="offers-item__price text-center" v-else>
                                Договорная
                            </td>
                            <td class="offers-item__old-price text-center">
                                <span v-if="number.price != number.final_price">{{ number.price }} ₽</span>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>

                <div style="margin: 20px 0 10px 0">

                    <div class="order-h1 big-h1">Ваши контакты</div>
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
                            <button class="button" v-on:click.prevent="oneClickOrder">Оформить заказ</button>
                        </div>
                    </div>

                </div>

            </div>
        </modal>-->

        <!--<modal name="number-order-popup" height="auto" :width="400" :reset="true" :adaptive="true" @before-open="resizeFix">
            <div class="modal-close" v-on:click="$modal.hide('number-order-popup')"></div>
            <div class="modal__content modal__padding-tb">
                <div class="modal__title">Номер под заказ</div>
                <div class="modal__desc">Оставьте контакты, и мы подберем для Вас желаемый номер!</div>
                <form @submit.prevent="submitOrderPopup">
                    <div class="modal__form">
                        <div class="modal__form-input">
                            <input type="text" placeholder="Желаемый номер телефона" v-model="number_order.number">
                        </div>
                        <div class="modal__form-input">
                            <input type="text" placeholder="Ваше имя" v-model="number_order.name">
                        </div>
                        <div class="modal__form-input">
                            <input type="text" placeholder="Ваш номер" v-mask="'+7 (###) ###-##-##'" v-model="number_order.phone">
                        </div>
                    </div>
                    <div class="modal__form-button">
                        <button type="submit">Отправить</button>
                    </div>
                </form>
                <div class="modal__policy">
                    Отправляя заявку вы соглашаетесь с <a href="/policy">политикой</a> обработки персональных данных
                </div>
            </div>
        </modal>-->
    </div>
</template>

<script>
    import OneClickBuyPopup from './modals/OneClickBuyModal.vue';
    import NumberOrderModal from './NumberOrderModal.vue'
    import InfoPopup from './modals/InfoPopup.vue';
    import CallbackModal from './modals/CallbackModal.vue';

    import VueTheMask from 'vue-the-mask'
    Vue.use(VueTheMask);
    import vueSlider from 'vue-slider-component'
    import VTooltip from 'v-tooltip'
    Vue.use(VTooltip);


    export default {
        props: ['price_max', 'price_range', 'promo', 'cart'],

        mounted() {
            this.form.perpage = this.perpage_options[0];
            this.form.sort = this.sort_options[0];
            this.search();

            $(this.$el).find('.filter-block__checkboxes .checkbox input').on('change', this.dataLayerFunction);
            $(this.$el).find('.filter-block__price input').on('input', this.dataLayerFunction);
        },

        data() {

            return {
                dataLayerFunction: function () {
                    if (!getCookie('lead__touch_beauty_number_form_done')) {
                        addToDataLayer({'event':'lead__touch_beauty_number_form'});
                        setCookie('lead__touch_beauty_number_form_done', 1, getInfiniteUTCDateString());
                    }
                },
                modalWidth: 300,
                number: {},
                order: {
                    name: '',
                    phone: '',
                    address: '',
                    policy: false
                },

                number_order: {
                    name: '',
                    number: '',
                    phone: ''
                },

                numbers: [],
                numbers_count: 0,

                form: {
                    price: [0, this.price_max],
                    perpage: {},
                    sort: {},
                    position_start: false,
                    position_middle: false,
                    position_end: false,
                    search: '',
                    page: 1,
                    promo: false,
                    pair: false
                },

                perpage_options: [
                    {name: '20 шт.', value: 20},
                    {name: '50 шт.', value: 50},
                    {name: '70 шт.', value: 70},
                    {name: '100 шт.', value: 100},
                ],

                sort_options: [
                    {name: 'От дешевых к дорогим', field: 'price_new', by: 'asc'},
                    {name: 'От дорогих к дешевым', field: 'price_new', by: 'desc'},
                    {name: 'Новизне', field: 'id', by: 'desc'},
                ],

                processStyle: {
                    "backgroundColor": "#f7d23e"
                }

            }

        },

        watch: {
            'form.search': function (newValue, oldValue) {
                this.dataLayerFunction();
            }
        },

        created() {
            if(this.price_range) {
                this.form.price = this.price_range;
            }

            if(this.promo) {
                this.form.promo = true;
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

            openNumberOrderPopup() {
                this.$modal.show(NumberOrderModal, {

                }, {
                    height: 'auto',
                    adaptive: true,
                    width: 400
                })
            },

            resizeFix() {
                //window.dispatchEvent(new Event('resize'))
            },

            submitOrderPopup() {

                if(this.number_order.phone.length < 18) {
                    this.$modal.show('dialog', {
                        title: 'Ошибка!',
                        text: 'Проверьте корректность введенного номера телефона',
                        buttons: [
                            {
                                title: 'Закрыть'
                            }
                        ]
                    });

                    return false;
                }

                this.$modal.show('dialog', {
                    title: 'Спасибо за заявку!',
                    text: 'Мы свяжемся с Вами в ближайшее время для уточнения всех деталей',
                    buttons: [
                        {
                            title: 'Закрыть'
                        }
                    ]
                });

                this.$modal.hide('number-order-popup');

            },

            openModal(subject, leadName) {

                var params = {
                    subject: subject
                };

                if (leadName) {
                    params = Object.assign({}, params, {leadName: leadName});
                }
                return this.$modal.show(CallbackModal, params, {
                    height: 'auto',
                    adaptive: true,
                    width: 400
                });
            },

            search() {


                this.form.page = 1;

                this.$http.post('/numbers', this.form).then(response => {

                    this.numbers = [];
                    let obj = response.body.numbers;
                    for (let key  in obj) {

                        this.numbers.push(obj[key]);
                    }

                    this.numbers_count = response.body.count;

                }, response => {

                    alert('Произошла ошибка при загрузке данных');

                });

            },

            setPattern(pattern) {

                this.numbers = [];
                this.form.search = pattern;
                this.search();

            },

            perpage(selectedOption) {

                this.form.perpage = selectedOption;
                this.numbers = [];
                this.form.page = 1;
                this.search();

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

            nextNumbers() {

                this.form.page++;

                this.$http.post('/numbers', this.form).then(response => {

                    let obj = response.body.numbers;
                    for (let key  in obj) {
                        this.numbers.push(obj[key]);
                    }

                    this.numbers_count = response.body.count;

                }, response => {

                    alert('Произошла ошибка при загрузке данных');

                });


            },

            oneClick(number) {

                this.$modal.show(OneClickBuyPopup, {
                    number: number
                }, {
                    height: 'auto',
                    adaptive: true,
                    width: 400,
                    scrollable: true
                });

            },

            /*
            oneClickOrder() {

                if(this.order.phone.length < 18) {
                    this.$modal.show('dialog', {
                        title: 'Ошибка!',
                        text: 'Проверьте корректность введенного номера телефона',
                        buttons: [
                            {
                                title: 'Закрыть'
                            }
                        ]
                    });

                    return false;
                }

                if(!this.order.policy) {
                    this.$modal.show('dialog', {
                        title: 'Ошибка!',
                        text: 'Необходимо принять условия перед оформлением заказа.',
                        buttons: [
                            {
                                title: 'Закрыть'
                            }
                        ]
                    });

                    return false;

                }

                this.$http.post('/cart/order/one-click', {
                    id: this.number.id,
                    name: this.order.name,
                    phone: this.order.phone,
                    address: this.order.address
                }).then(response => {

                    this.$modal.hide('one-click');

                    this.$modal.show('dialog', {
                        title: 'Заказ успешно оформлен',
                        text: 'Мы свяжемся с вами в ближайшее время.',
                        buttons: [
                            {
                                title: 'Закрыть'
                            }
                        ]
                    });

                    this.order = {
                        name: '',
                        phone: '',
                        address: '',
                        policy: false
                    };

                }, response => {

                    alert('Произошла ошибка при загрузке данных');

                });

            },*/

            sort(selected) {
                this.form.sort = selected;
                this.search();
            }

        },

        components: {
            vueSlider
        }
    }
</script>

<style>
    .filter-block__template-info span {
        cursor: pointer;
    }
</style>