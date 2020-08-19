<template>
    <div class="container">
        <div class="columns">
            <div class="column col-12">
                <h1>
                    My Stories
                </h1>
            </div>
        </div>
        <div v-if="isLoading" class="columns">
            <LoadingPageComponent></LoadingPageComponent>
        </div>
        <div v-else class="columns">
            <StoryCardComponent v-for="story in stories" :key="story.data.id" :story="story.data"></StoryCardComponent>
        </div>
    </div>
</template>

<script>
import LoadingPageComponent from '../components/LoadingPageComponent'
import StoryCardComponent from '../components/StoryCardComponent'
export default {
    name: "MyStoriesView",

    components: {
        LoadingPageComponent,
        StoryCardComponent
    },

    data: function () {
        return {
            stories: '',
            isLoading: true
        }
    },

    mounted() {
        axios.get('/api/stories/?mystories=1')
            .then(response => {
                this.stories = response.data.data;
                this.isLoading = false;
            })
            .catch(error => {
                this.isLoading = false;
            });
    }
}
</script>

<style>

</style>