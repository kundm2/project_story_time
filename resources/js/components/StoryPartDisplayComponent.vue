<template>
    
    <div class="column col-12">
        <img v-if="storyPart.is_image" :src="imageSrc">
        
        <p v-else class="h5">
            {{ storyPart.content }}
        </p>
        
        <div class="divider"></div>
    </div>

    
</template>

<script>
export default {
    name: "StoryPartDisplayComponent",

    props: [
        'storyPart'
    ],

    computed: {
        imageSrc() {
            return '/images/' + this.storyPart.content;
        },
    }

}
</script>

<style>

</style>