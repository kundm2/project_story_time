<template>
    <div class="column col-3">
        <div class="card">
            <div class="card-header">
                <div class="card-subtitle text-gray">
                    {{story.createdAt}}
                </div>
            </div>
            <div class="card-body">
                <p>
                    {{story.parts[0].data.content}}
                </p>
            </div>
            <div class="card-footer">
                <router-link v-if="story.isFinished" :to="'stories/' + story.id" class="btn btn-primary btn-block">View</router-link>
                
                <button v-else class="btn btn-link btn-block" disabled>
                        {{story.parts.length}} / 7 - in progress
                </button>
                <progress v-if="!story.isFinished" class="progress" :value="storyProgress" max="100"></progress>
            </div>
        </div>
    </div>
</template>
 
<script>
export default {
    name: 'StoryCardComponent',

    props:[
        'story'
    ],

    computed: {
        storyProgress() {
            return this.story.parts.length / 7 * 100;
        },
    }
}
</script>

<style>

</style>