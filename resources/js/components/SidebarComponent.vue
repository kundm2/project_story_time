<template>
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
</template>

<script>
export default {
    name: 'SidebarComponent',

    props: [
        'links'
    ]

}
</script>

<style>

</style>