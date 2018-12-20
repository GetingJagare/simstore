<template>
    <div>
        <div class="main-wrap d-flex flex-nowrap justify-content-between">
            <div class="main-content main-content-tariffs">
                <form @submit.prevent="search">
                    <div class="filter-block filter-tariffs">
                        <div class="filter-block__checkboxes">
                            <label class="checkbox">
                                <input type="checkbox" v-model="form.promo"/>
                                <span class="checkbox__text">Акция</span>
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" v-model="form.no_limit"/>
                                <span class="checkbox__text">Безлимитные</span>
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" v-model="form.no_limit_ru"/>
                                <span class="checkbox__text">Безлимит — Россия</span>
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" v-model="form.for_internet"/>
                                <span class="checkbox__text">Для интернета</span>
                            </label>
                        </div>
                        <div class="d-flex flex-wrap flex-md-nowrap">
                            <div class="filter-block__price filter-minutes">

                                <div class="d-flex flex-nowrap align-items-center">
                                    <span class="filter-block__price-name">Количество <span class="md-br">минут:</span></span>
                                    <input type="text" placeholder="от" v-model="form.minutes[0]">
                                    <span>–</span>
                                    <input type="text" placeholder="до" v-model="form.minutes[1]">
                                </div>

                                <div class="filter-block__range">
                                    <vue-slider v-model="form.minutes" :min="0" :max="5000" :process-dragable="true"
                                                :tooltip="false" :process-style="processStyle"
                                                @drag-end="dataLayerFunction"></vue-slider>
                                </div>
                            </div>
                            <div class="filter-block__price">

                                <div class="d-flex flex-nowrap align-items-center">
                                    <span class="filter-block__price-name">Абонентская <span class="md-br">плата:</span></span>
                                    <input type="text" placeholder="от" v-model="form.price[0]">
                                    <span>–</span>
                                    <input type="text" placeholder="до" v-model="form.price[1]">
                                </div>

                                <div class="filter-block__range">
                                    <vue-slider v-model="form.price" :min="0" :max="5000" :process-dragable="true"
                                                :tooltip="false" :process-style="processStyle"
                                                @drag-end="dataLayerFunction"></vue-slider>
                                </div>

                            </div>
                        </div>

                        <div class="d-flex flex-wrap flex-sm-nowrap">
                            <div class="filter-block__price">

                                <div class="d-flex flex-nowrap align-items-center">
                                    <span class="filter-block__price-name">Объем <span
                                            class="md-br">трафика:</span></span>
                                    <input type="text" placeholder="от" v-model="form.traffic[0]">
                                    <span>–</span>
                                    <input type="text" placeholder="до" v-model="form.traffic[1]">
                                </div>

                                <div class="filter-block__range">
                                    <vue-slider v-model="form.traffic" :min="0" :max="5000" :process-dragable="true"
                                                :tooltip="false" :process-style="processStyle"
                                                @drag-end="dataLayerFunction"></vue-slider>
                                </div>

                            </div>
                        </div>

                        <div class="filter-block__buttons">
                            <button class="filter-block__buttons-search" type="submit">Найти</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class="tariffs-result">
            <div class="container">

                <div class="tariffs-result__header d-flex flex-wrap flex-sm-nowrap justify-content-between align-items-center">
                    <div class="tariffs-result__title">Найдено {{ tariffs.length }} тарифов</div>
                    <div>
                        <div class="d-flex flex-wrap flex-sm-nowrap justify-content-between align-items-center">

                            <div class="d-flex flex-wrap flex-lg-nowrap align-items-center tariffs-sort-options">
                                <div class="main-content__select-count">Сортировать по:</div>
                                <multiselect @select="sort" v-model="form.sort" :options="sort_options"
                                             :allow-empty="false" track-by="name" label="name" :searchable="false"
                                             :close-on-select="true" :show-labels="false">
                                    <template slot="singleLabel" slot-scope="{ option }">{{ option.name }}</template>
                                </multiselect>
                            </div>

                            <div class="w-xs-100 offer-help">
                                <a href="#" class="button no-bg icon-phone"
                                   v-on:click.prevent="openModal('Помогите с выбором', 'lead_upper_popup_form')">Помогите
                                    с выбором</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tariffs-items">

                    <div class="tariffs-items__row row">


                        <div class="col-lg-6 col-xl-4" v-for="tariff in tariffs">
                            <div class="tariffs-item">
                                <div class="tariffs-item__label" v-if="tariff.label"><span>{{ tariff.label }}</span>
                                </div>
                                <div class="tariffs-item__header d-flex flex-nowrap align-items-center">
                                    <div class="tariffs-item__operator">
                                        <div class="tariffs-icon beeline"></div>
                                    </div>
                                    <div class="tariffs-item__name">{{ tariff.name }}</div>
                                </div>
                                <div class="tariffs-item__content">
                                    <div class="tariffs-item__line time">{{ tariff.minutes }} мин. по России</div>
                                    <div class="tariffs-item__line sms">{{ tariff.sms }} SMS по России</div>
                                    <div class="tariffs-item__line net">{{ tariff.traffic }} ГБ интернета</div>
                                    <div class="tariffs-item__line vk" v-if="tariff.socials">{{ tariff.socials }}</div>
                                    <div class="tariffs-item__line wp" v-if="tariff.messengers">{{ tariff.messengers
                                        }}
                                    </div>
                                    <div class="tariffs-item__line yt" v-if="tariff.youtube">{{ tariff.youtube }}
                                        YouTube
                                    </div>
                                </div>
                                <div class="tariff-item_bottom">
                                    <div class="tariffs-item__price">
                                        <span><b>{{ tariff.final_price }} ₽</b> / месяц</span><br>
                                        из любой точки России
                                    </div>
                                    <div class="tariffs-item__buttons d-flex flex-nowrap">
                                        <!--<a href="#" class="button no-bg tariffs-item__buttons-more" v-on:click.prevent="detail(tariff)">Узнать подробнее</a>-->
                                        <a href="#" class="button no-bg tariffs-item__buttons-more"
                                           v-on:click.prevent="openModal('Узнать подробнее', null, tariff.name)">Узнать
                                            подробнее</a>
                                        <!--<a href="#" v-if="cart.tariffs.includes(tariff.id)" class="tariffs-item__buttons-cart active" v-on:click.prevent="showNumberAlreadyInCartModal">В корзину</a>-->
                                        <a href="#" class="tariffs-item__buttons-cart"
                                           v-on:click.prevent="openModal('Узнать подробнее', 'basket_add', tariff.name)">В
                                            корзину</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>

            </div>
        </div>

    </div>
</template>

<script>
    import CallbackModal from './modals/CallbackModal.vue';
    import InfoPopup from './modals/InfoPopup.vue';
    import TariffDetails from './modals/TariffDetails.vue';

    import VModal from 'vue-js-modal'
    import Multiselect from 'vue-multiselect'
    import vueSlider from 'vue-slider-component'
    import {VueMasonryPlugin} from 'vue-masonry';

    Vue.use(VModal);
    Vue.use(VueMasonryPlugin);
    Vue.component('multiselect', Multiselect);

    export default {
        props: ['unlimited', 'unlimited_ru', 'for_internet', 'cart'],

        mounted() {

            this.form.sort = this.sort_options[1];
            this.search();

            $(this.$el).find('.filter-block__checkboxes .checkbox input').on('change', this.dataLayerFunction);
            $(this.$el).find('.filter-block__price input').on('input', this.dataLayerFunction);
        },

        data() {

            return {
                dataLayerFunction: function () {
                    if (!getCookie('lead__touch_beauty_number_form_done')) {
                        addToDataLayer({'event': 'lead__touch_beauty_number_form'});
                        setCookie('lead__touch_beauty_number_form_done', 1, getInfiniteUTCDateString());
                    }
                },
                tariffs: [],
                tariff_detail: {},

                form: {
                    minutes: [0, 5000],
                    price: [0, 5000],
                    traffic: [0, 5000],
                    promo: false,
                    no_limit: false,
                    no_limit_ru: false,
                    for_internet: false,
                    sort: ''

                },

                processStyle: {
                    "backgroundColor": "#f7d23e"
                },

                sort_options: [
                    {name: 'От дешевых к дорогим', field: 'price', by: 'asc'},
                    {name: 'От дорогих к дешевым', field: 'price', by: 'desc'},
                ]

            }

        },

        created() {

            if (this.unlimited) {
                this.form.no_limit = true;
            }

            if (this.unlimited_ru) {
                this.form.no_limit_ru = true;
            }

            if (this.for_internet) {
                this.form.for_internet = true;
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

            openModal(subject, leadName, tariffName) {
                var params = {
                    subject: subject
                };
                if (leadName) {
                    addToDataLayer({event: leadName});
                }

                if (tariffName) {
                    params = Object.assign({}, params, {tariffs: tariffName});
                }

                return this.$modal.show(CallbackModal, params, {
                    height: 'auto',
                    adaptive: true,
                    width: 400
                });

            },

            search() {

                this.$http.post('/tariffs', this.form).then(response => {

                    this.tariffs = response.body.tariffs;

                }, response => {

                    alert('Произошла ошибка при загрузке данных');

                });
            },

            detail(item) {

                return this.$modal.show(TariffDetails, {
                    tariff: item
                }, {
                    height: 'auto',
                    adaptive: true,
                    width: 370,
                    scrollable: true
                });

                /*this.tariff_detail = item;
                this.$modal.show('tariff-detail');*/

            },

            addToCart(item) {

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
                    document.getElementsByClassName('basket-mobile-count')[0].innerHTML = response.body.count;

                    addToDataLayer({'event': 'basket_add'});

                }, response => {

                    alert('Произошла ошибка при загрузке данных');

                });

            },

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