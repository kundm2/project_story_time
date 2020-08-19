import Vue from 'vue';
import VueRouter from 'vue-router';

import BrowseView from './views/BrowseView.vue';
import StoriesView  from './views/StoriesView.vue';
import SingleStoryView  from './views/SingleStoryView.vue';
import PlayView from './views/PlayView.vue';
import RatingView from './views/RatingView.vue';

import PageNotFoundView from './views/PageNotFoundView.vue';


Vue.use(VueRouter);

export default new VueRouter({
    routes: [

        {path: '/', component: PlayView},
        {path: '/stories', component: StoriesView},
        {path: '/stories/:id', component: SingleStoryView},
        {path: '/browse', component: BrowseView},
        {path: '/rating', component: RatingView},

        { path: "*", component: PageNotFoundView }
    ],

    linkExactActiveClass: "active", // active class for *exact* links.

    mode: 'history' // Using push state
});
