require('./bootstrap');

import Vue from 'vue';
import router from './router'
import store from './store'

import 'bootstrap/dist/css/bootstrap.css'

import animateCss from 'animate.css';
Vue.use(animateCss);

import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
Vue.use(ViewUI);

import VueGoodTablePlugin from 'vue-good-table';

// import the styles
import 'vue-good-table/dist/vue-good-table.css'

Vue.use(VueGoodTablePlugin);

import VueTelInput from 'vue-tel-input';
import 'vue-tel-input/dist/vue-tel-input.css'

Vue.use(VueTelInput)

import VueProgressBar from 'vue-progressbar'

Vue.use(VueProgressBar, {
  color: 'green',
  failedColor: 'red',
  height: '4px'
}) 

import common from './common'
Vue.mixin(common);




Vue.component('mainapp', require('./components/mainapp.vue').default); 
const app = new Vue({
    el: '#app',
    router,
    store
})