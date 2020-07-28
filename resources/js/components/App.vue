<template>
    <div>
        {{user}}
        <router-view></router-view>
    </div>

</template>

<script>
    export default {
        name: 'App',

        props: [
            'user'
        ],

    }
</script>

<style lang="scss" scoped>
</style>
