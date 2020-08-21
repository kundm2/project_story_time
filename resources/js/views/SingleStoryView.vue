<template>
    <div class="container">
        
        <div v-if="isLoading" class="columns">
            <LoadingPageComponent></LoadingPageComponent>
        </div>

        <div v-else class="columns col-gapless">
            <div class="play-canvas col-10 col-mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="columns">
                            <div class="column col-4">
                                <p class="text-gray">
                                    Created {{story.createdAt}}
                                </p>
                            </div>
                            
                            <div class="column col-4">
                                <p class="text-center">
                                </p>
                            </div>

                            <div class="column col-4">
                                <p class="text-primary text-right">
                                    <StarRatingComponent :rating="story.rating"></StarRatingComponent>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="columns">
                            <StoryPartDisplayComponent v-for="part in story.parts" :key="part.data.id" :storyPart="part.data"></StoryPartDisplayComponent>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingPageComponent from '../components/LoadingPageComponent';
import StoryPartDisplayComponent from '../components/StoryPartDisplayComponent';
import StarRatingComponent from '../components/StarRatingComponent';

export default {
    name: "SingleStoryView",
    
    components: {
        LoadingPageComponent,
        StoryPartDisplayComponent,
        StarRatingComponent
    },

    data: function () {
        return {
            story: [],
            isLoading: true
        }
    },

    mounted() {
        axios.get('/api/stories/' + this.$route.params.id)
            .then(response => {
                this.story = response.data.data;
                if (!this.story.isFinished) {
                    this.$router.push('/stories');
                }
                this.isLoading = false;
            })
            .catch(error => {
                this.$router.push('/stories');
                this.isLoading = false;
            });
    }
}
</script>

<style>

</style>