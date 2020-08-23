<template>

    <ul v-if="maxPages > 1" class="pagination">
        <li class="page-item page-prev" :class="{'disabled': initialPage === 1}">
            <router-link :to="'/stories?page=' + (initialPage - 1)" :click="pageSwitched()">
                <div class="page-item-subtitle">Go</div>
                <div class="page-item-title h5">Back</div>
            </router-link>
        </li>
        <li class="page-item page-next" :class="{'disabled': initialPage === maxPages}">
            <router-link :to="'/stories?page=' + (initialPage + 1)" :click="pageSwitched()">
                <div class="page-item-subtitle">More</div>
                <div class="page-item-title h5">Stories</div>
            </router-link>
        </li>
    </ul>
</template>

<script>
    // Source: https://jasonwatmore.com/post/2019/08/21/vue-js-simple-pagination-example

    export default {
        props: {
            initialPage: {
                type: Number,
                default: 1
            },
            maxPages: {
                type: Number,
                required: true
            }
        },

        methods: {
            pageSwitched() {
                this.$emit('changePage');
            }
        }
    }
</script>