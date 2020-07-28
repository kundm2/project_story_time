import Vue from 'vue';
import VueRouter from 'vue-router';

import ExampleComponent from './components/ExampleComponent'


Vue.use(VueRouter);

export default new VueRouter({
    routes: [

        {path: '/', component: ExampleComponent},

        //{ path: "*", component: PageNotFoundView }
    ],

    linkExactActiveClass: "active", // active class for *exact* links.

    mode: 'history' // Using push state
});
