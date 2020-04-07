try {
    window.$ = window.jQuery = require('jquery');

    require('fancybox')($);
    require('owl.carousel');

    //require('bootstrap');
} catch (e) {}

$(document).ready(function(){

    $('.owl-slider-home').owlCarousel({
        items: 1,
        nav: true,
        dots: false,
        navText: ['','']
    });

    $('[data-href]').on('click', function () {
        window.location.href = $(this).data('href');
    });

    $('.open-dropdown').on('click', function() {
        $(this).parent().children('ul.dropdown').toggleClass('opened');

        return false;
    });

    $(document).on('click', function (e) {
        if ($(e.target).closest("ul.dropdown.opened").length === 0) {
            $("ul.dropdown.opened").removeClass('opened');
        }
    });

    /*
    $(document).mouseup(function(e)
    {

        if(window.outerWidth > 576 && window.outerWidth < 992) {

            var container = $("ul.dropdown.opened");

            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0)
            {
                container.removeClass('opened');
            }
        }

    });*/
});

window.addToDataLayer = function(data) {
    if (window.dataLayer) {
        dataLayer.push(data);
    }
};

window.getInfiniteUTCDateString = function () {
    const date = new Date();
    date.setUTCHours(23, 59, 59);
    date.setUTCFullYear(2038, 0, 18);
    return date.toUTCString();
};

window.getCookie = function (key) {
    const regEx = new RegExp(key + '=([^;]+)');
    if (regEx.test(document.cookie)) {
        return document.cookie.match(regEx)[1];
    }
    return null;
};

window.setCookie = function (key, value, expires) {
    document.cookie = key + '=' + value + ';path=/;domain=.' + window.location.host + ';expires=' + expires;
};

window.deleteCookie = function (key) {
    const date = new Date();
    const previousDate = (new Date(date.getTime() - 1)).toUTCString();
    document.cookie = key + '=;path=/;domain=.sim-store.ru;expires=' + previousDate;
};

window.setUTMTags = function () {
    var data = getUTMTags();

    if (Object.keys(data).length) {
        setCookie('utm_tags', encodeURIComponent(JSON.stringify(data)), getInfiniteUTCDateString());
    }
};

window.getUTMTags = function () {
    var data = {}, utmTags;

    if (utmTags = getCookie('utm_tags')) {
        utmTags = JSON.parse(decodeURIComponent(utmTags));
        if (utmTags) {
            for (var param in utmTags) {
                data[param] = utmTags[param];
            }
        }
    }

    if (/utm_[^=]+=[^&]+/.test(window.location.href)) {
        const queryString = window.location.href.match(/\?(.+)$/)[1];
        queryString.split('&').forEach(function (paramValue) {
            const splittedParam = paramValue.split('=');
            if (/^utm_.+$/.test(splittedParam[0])) {
                data[splittedParam[0]] = decodeURIComponent(splittedParam[1]);
            }
        });
    }

    return data;
};

window.getGACookies = function () {
    const keys = {'_ga': 'ga'};
    var data = {};
    for (var cookie_key in keys) {
        var value;
        if (value = getCookie(cookie_key)) {
            switch (cookie_key) {
                case '_ga':
                    var matches = value.match(/^\w+\d+\.\d+\.(\d+\.\d+)$/i);
                    data[keys[cookie_key]] = matches ? matches[1] : null;
                    break;
                default:
                    data[keys[cookie_key]] = value;
                    break;
            }
        }
    }
    return data;
};

window.Vue = require('vue');
window.VueEvent = require('vue-event-manager');

var VueResource = require('vue-resource');
Vue.use(VueResource);
Vue.http.headers.common['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]').content;

import VModal from 'vue-js-modal'

Vue.use(VModal, { dialog: true,  dynamic: true  });




Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('numbers-filter', require('./components/NumbersFilter.vue'));
Vue.component('numbers-filter2', require('./components/NumbersSortFilter.vue'));
Vue.component('select-city', require('./components/SelectCity.vue'));
Vue.component('tariffs', require('./components/Tariffs.vue'));
Vue.component('numbers', require('./components/Numbers.vue'));
Vue.component('cart', require('./components/Cart.vue'));
Vue.component('promo-numbers', require('./components/PromoNumbers.vue'));
Vue.component('promo-tariffs', require('./components/PromoTariffs.vue'));
Vue.component('countdown', require('./components/Countdown.vue'));
Vue.component('callback-popup', require('./components/CallbackPopup.vue'));
Vue.component('callback-modal', require('./components/modals/CallbackModal'));
Vue.component('login-form', require('./components/LoginForm'));
Vue.component('lostpass-form', require('./components/LostPassForm'));
//Vue.component('order', require('./components/Order.vue'));

import CallbackModal from './components/modals/CallbackModal.vue';
import InfoPopup from './components/modals/InfoPopup.vue';

const app = new Vue({
    el: '#app',

    data: {

        cart: [],

        modal_callback_subject: 'Подключить',
        modal_callback: {
            name: '',
            phone: ''
        },

        question: {
            subject: 'Остались вопросы',
            name: '',
            phone: '',
            tariffs: 'Заказан звонок с сайта'
        }

    },


    created: function () {

        this.cart = CART;
    },

    mounted() {
        setUTMTags();
    },

    methods: {
        
        openCallbackPopup: function (subject, leadName, tariffs) {

            var params = {
                subject: subject
            };

            if (leadName) {
                params = Object.assign({}, params, {leadName: leadName});
            }

            if (tariffs) {
                params = Object.assign({}, params, {tariffs: tariffs});
            }

            this.$modal.show(CallbackModal, params, {
                height: 'auto',
                adaptive: true,
                width: 400
            });

            /*this.modal_callback_subject = subject ? subject : 'Подключить';
            this.$modal.show('callback-popup');*/

        },

        submitCallbackPopup: function () {

            if(this.modal_callback.phone.length < 18) {
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

            this.$modal.hide('callback-popup');

            this.modal_callback = {
                name: '',
                phone: ''
            }

        },
        
        submitQuestionForm: function () {

            if(this.question.phone.length < 18) {

                this.$modal.show(InfoPopup, {
                    title: 'Ошибка!',
                    text: 'Проверьте корректность введенного номера телефона'
                }, {
                    height: 'auto',
                    adaptive: true,
                    width: 400
                });

                return false;
            }

            var params = Object.assign({}, this.question, getUTMTags());

            this.$http.post('/crm', {fields: params}).then(response => {

                this.$modal.show(InfoPopup, {
                    title: 'Спасибо за заявку!',
                    text: 'Мы свяжемся с Вами в ближайшее время для уточнения всех деталей'
                }, {
                    height: 'auto',
                    adaptive: true,
                    width: 400
                });

                addToDataLayer({'event':'lead_questions_form'});

            }, response => {

                alert('Произошла ошибка.')

            });



            this.question = {
                name: '',
                phone: ''
            }

        },

        openMenu: function (e) {
            e.preventDefault();
            var menu = document.querySelector('nav'); // Using a class instead, see note below.
            menu.classList.toggle('opened');
        }

    },

    events: {

        updateCart: function (event, param) {

            if(param.numbers) {
                this.cart.numbers = param.numbers;
            }

            if(param.tariffs) {
                this.cart.tariffs = param.tariffs;
            }

        }
    }
    
});

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
/*
require('./bootstrap');

window.Vue = require('vue');



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
/*
Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});
*/