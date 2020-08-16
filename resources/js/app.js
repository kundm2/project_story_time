import Vue from 'vue';
import router from './router';
import App from './App.vue'

// Additional Libraries

require('./bootstrap');
require('fabric');

const app = new Vue({
    el: '#app',
    components: {
        App
    },
    router
});
