/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import VueRouter from 'vue-router'
import VueSweealert2 from 'vue-sweetalert2'
import * as VeeValidate from 'vee-validate';
import { required, email } from 'vee-validate/dist/rules';
import { ValidationProvider, extend } from 'vee-validate';
Vue.component('ValidationProvider', ValidationProvider);
Vue.use(VueRouter)
Vue.use(VueSweealert2)
Vue.use(VeeValidate);

import App from './components/App.vue'
import ClientIndex from './components/Client/ClientIndex.vue'
import ProductIndex from './components/Product/ProductIndex.vue'
import SaleIndex from './components/Sale/SaleIndex.vue'

// No message specified.
extend('email', email);

// Override the default message.
extend('required', {
  ...required,
  message: 'Este campo es requerido'
});

const router = new VueRouter({
    mode:'history',
    routes:[
        {
            path:'/clients/',
            component: ClientIndex
        },
        {
            path:'/products',
            component: ProductIndex
        },
        {
            path:'/sales',
            component: SaleIndex
        }
    ]

})
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

import Vuetify from 'vuetify'
import Vue from 'vue';
import { add } from 'lodash';
Vue.use(Vuetify);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components:{App},
    router
});
