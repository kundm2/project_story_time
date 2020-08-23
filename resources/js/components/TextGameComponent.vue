<template>
    <div class="columns col-gapless drawing-canvas">
        <template v-if="story != '' && story !== undefined">
            <div class="column col-12">
                <img :src="imageSrc">
            </div>
            <div class="column col-12">
                <div class="divider text-center" data-content="What are you seeing here?"></div>
            </div>
        </template>
        <div class="column col-12">
            <textarea class="form-input" rows="4" v-model="content" :maxlength="charLimit" @keyup="somethingTyped" autofocus></textarea>
            <span :class="charsLeftClass">{{content.length}} / {{charLimit}}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: "TextGameComponent",

    props: [
        'story',
        'charLimit'
    ],

    data: function () {
        return {
            content: ''
        }
    },

    computed: {
        charsLeftClass() {
            if (this.content.length > this.charLimit * 0.9) {
                return 'text-error';
            } else if (this.content.length > this.charLimit * 0.7) {
                return 'text-warning';
            } else{
                return '';
            }
        },
        imageSrc() {
            return 'images/' + this.story.parts[this.story.parts.length - 1].data.content;
        },
    },

    methods: {
        somethingTyped() {
            this.$emit('update:content', this.content);
        }
    }

}
</script>

<style>

</style>