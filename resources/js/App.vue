<template>
    <div class="main-container mainpage">

        <HeaderComponent :username="user.name"></HeaderComponent>

        <div class="sidebar-menu">
            <ul>
                <li v-for="(link, idx) in links" :key="idx">
                    <router-link class="btn btn-lg" :class="link.btnClass" :to="link.target">
                        <i class="fas" :class="link.icon"></i>
                        {{link.label}}
                    </router-link>
                </li>
            </ul>
        </div>

        <div class="language-switcher">
        </div>

        <main>
            <router-view></router-view>
        </main>

    </div>

</template>

<script>
    import HeaderComponent from './components/HeaderComponent'
    export default {
        name: 'App',

        components: {
            HeaderComponent
        },

        props: [
            'user'
        ],

        data: function () {
            return {
                links: [
                    {
                        target: '/',
                        label: 'Play',
                        icon: 'fa-palette',
                        btnClass: 'btn-primary'
                    },
                    {
                        target: '/my-stories',
                        label: 'My Stories ',
                        icon: 'fa-images',
                        btnClass: 'btn-link'
                    },
                    {
                        target: '/browse',
                        label: 'Browse',
                        icon: 'fa-tags',
                        btnClass: 'btn-link'
                    },
                    {
                        target: '/rating',
                        label: 'Rating',
                        icon: 'fa-star',
                        btnClass: 'btn-link'
                    },
                ]
            }
        },

    }
</script>

<style lang="scss" scoped>
</style>
