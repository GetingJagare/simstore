window.Vue = require('vue');
import Vue from 'vue';
import VueRouter from 'vue-router';
import BootstrapVue from 'bootstrap-vue';
var VueResource = require('vue-resource');

Vue.use(VueResource);
Vue.use(BootstrapVue);
Vue.use(VueRouter);

Vue.http.headers.common['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]').content;

Vue.component('auth', require('./components/admin/Auth.vue'));

const router = new VueRouter({
    mode: 'hash',
    routes: [
        {
            path: '/',
            name: 'home',
            component: { template: '<div>Добро пожаловать!</div>' }
        },
        {
            path: '/bar',
            name: 'bar',
            component: { template: '<div>bar</div>' },
        },
        {
            path: '/users',
            name: 'users',
            component: require('./components/admin/Users.vue')
        },
        {
            path: '/regions',
            name: 'regions',
            component: require('./components/admin/Regions.vue')
        },
        {
            path: '/regions/:id',
            name: 'regions_edit',
            component: require('./components/admin/regions/RegionEdit.vue')
        },
        {
            path: '/pages',
            name: 'pages',
            component: require('./components/admin/pages/Pages.vue')
        },
        {
            path: '/pages/:id',
            name: 'page_edit',
            component: require('./components/admin/pages/PageEdit.vue')
        },
        {
            path: '/tariffs',
            name: 'tariffs',
            component: require('./components/admin/tariffs/Tariffs.vue')
        },
        {
            path: '/tariffs/:id',
            name: 'tariffs_edit',
            component: require('./components/admin/tariffs/TariffEdit.vue')
        },
        {
            path: '/settings',
            name: 'settings',
            component: require('./components/admin/Settings.vue')
        },
        {
            path: '/numbers',
            name: 'numbers',
            component: require('./components/admin/Numbers.vue')
        },
        {
            path: '/orders',
            name: 'orders',
            component: require('./components/admin/orders/Orders.vue')
        },
        {
            path: '/orders/:id',
            name: 'orders_detail',
            component: require('./components/admin/orders/OrdersDetail.vue')
        },
        {
            path: '/numbers/import',
            name: 'numbers_import',
            component: require('./components/admin/NumbersImport.vue')
        },
        {
            path: '/cache',
            name: 'cache_page',
            component: require('./components/admin/CacheClear.vue')
        }
    ],
});

const app = new Vue({
    router
}).$mount('#app');