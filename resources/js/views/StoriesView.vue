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
            <StoryCardComponent v-for="story in stories.data" :key="story.data.id" :story="story.data"></StoryCardComponent>
        </div>
        <div class="colums">
            <PaginationComponent v-if="!isLoading"
                :items="stories.data"
                :initialPage="stories.meta.current_page"
                :pageSize="stories.meta.per_page"
                :maxPages="stories.meta.last_page"
                @changePage="loadStories"
            ></PaginationComponent>
        </div>
    </div>
</template>

<script>
import LoadingPageComponent from '../components/LoadingPageComponent'
import PaginationComponent from '../components/PaginationComponent'
import StoryCardComponent from '../components/StoryCardComponent'
export default {
    name: "MyStoriesView",

    components: {
        LoadingPageComponent,
        StoryCardComponent,
        PaginationComponent
    },

    data: function () {
        return {
            stories: '',
            isLoading: true
        }
    },

    mounted() {
        this.loadStories();
    },

    methods: {
        loadStories: function () {
            
            console.log('asdf');
            axios.get('/api/stories/?mystories=1&page=' + this.$route.query.page)
                .then(response => {
                    this.stories = response.data;
                    this.isLoading = false;
                })
                .catch(error => {
                    this.isLoading = false;
                });
        }
    }
}
</script>

<style>

</style>