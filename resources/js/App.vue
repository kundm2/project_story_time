<template>
    <div class="main-container mainpage">

        <HeaderComponent :username="user.name"></HeaderComponent>

        <div class="sidebar-menu">
            <ul>
                <li v-for="(link, idx) in links" :key="idx">
                    <router-link class="btn btn-lg btn-desktop" :class="link.btnClass" :to="link.target">
                        <i class="fas" :class="link.icon"></i>
                        {{link.label}}
                    </router-link>
                    <router-link class="btn btn-lg btn-mobile tooltip tooltip-right" :class="link.btnClass" :to="link.target" :data-tooltip="link.label">
                        <i class="fas" :class="link.icon"></i>
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
