<template>
    <header>
        <div class="columns col-gapless">
            <div class="column col-12">
                <div class="float-right">
                    <!-- basic dropdown button -->
                    <div class="dropdown dropdown-right">
                        <a href="#" class="btn btn-link dropdown-toggle" tabindex="0">
                            {{username}}&nbsp;<i class="fas fa-caret-down"></i>
                        </a>
                        <!-- menu component -->
                        <ul class="menu">
                            <li v-for="(link, idx) in links" :key="idx" class="menu-item">
                                <router-link :to="link.target">
                                    {{link.label}}
                                </router-link>
                            </li>
                            <li class="menu-item">
                                <a href="/logout">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
export default {
    name: 'HeaderComponent',

    props: [
        'username'
    ],

    data: function () {
        return {
                links: [
                    {
                        target: '/profile',
                        label: 'Profile',
                    }
                ]
        };
    }
}
</script>

<style lang="scss" scoped>
    header {
        div.dropdown {
            margin: 7px 0;
        }
    }
</style>