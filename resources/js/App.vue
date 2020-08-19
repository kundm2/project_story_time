<template>
    <div class="main-container mainpage">

        <HeaderComponent :username="user.name"></HeaderComponent>

        <div class="sidebar-menu">
            <SidebarComponent :links="links"></SidebarComponent>
        </div>

        <div class="language-switcher">
        </div>

        <main>
            <router-view></router-view>
        </main>

    </div>

</template>

<script>
    import HeaderComponent from './components/HeaderComponent';
    import SidebarComponent from './components/SidebarComponent';
    export default {
        name: 'App',

        components: {
            HeaderComponent,
            SidebarComponent
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
                        target: '/stories',
                        label: 'My Stories ',
                        icon: 'fa-images',
                        btnClass: 'btn-link'
                    },
                    {
                        target: '/browse',
                        label: 'Browse',
                        icon: 'fa-search',
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

        created() {
            axios.interceptors.request.use(
                (config) => {
                    if (config.method === 'get') {
                        config.url = config.url + ((config.url.includes("?")) ? '&' : '?') + 'api_token=' + this.user.api_token;
                    } else {
                        config.data = {
                            ...config.data,
                            api_token: this.user.api_token
                        };
                    }
                    return config;
                }
            );
        },
    }
</script>

<style lang="scss" scoped>
</style>
