<template>
    <div class="columns col-gapless">
        <div class="play-canvas col-10 col-mx-auto">
            <div class="card">
                <div class="card-header">
                    <div class="card-title h5">
                    </div>
                    <div class="card-subtitle text-gray">
                        <div class="text-right">
                            <a href="#">How it works?</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div v-if="isNewStory || isNextStoryPartText">
                        <TextGameComponent :story="story" :charLimit="charLimit" @update:content="form.content = $event"></TextGameComponent>
                    </div>
                    <div v-else>
                        <DrawingGameComponent :story="story" @update:content="form.content = $event"></DrawingGameComponent>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary" @click="submitForm">
                        Send
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import DrawingGameComponent from '../components/DrawingGameComponent';
    import TextGameComponent from '../components/TextGameComponent';

    export default {
        name: "PlayView",
        
        components: {
            DrawingGameComponent,
            TextGameComponent
        },

        mounted() {
            // Get a random chance to start a new story
            var d = new Date();
            if (d.getTime() % 10 > 0) {
                axios.get('/api/stories/?play=1')
                    .then(response => {
                        this.story = response.data.data;
                        // Figure out which component to load
                        if (this.story === undefined) {
                            this.isNewStory = true;
                        } else {
                            this.isNextStoryPartText = (this.story.parts[this.story.parts.length - 1].data.is_image == 1);
                            this.form.is_image = !(this.story.parts[this.story.parts.length - 1].data.is_image);
                        }
                        this.isLoading = false;
                    })
                    .catch(error => {
                        this.isLoading = false;
                    });
            } else {
                this.isNewStory = true;
            }
        },

        data: function () {
            return {
                form: {
                    'content': '',
                    'language': 'en',
                    'is_image': false
                },
                story: '',
                isLoading: true,
                isNewStory: false,
                isNextStoryPartText: true,
                charLimit: 200,
            }
        },
    
        methods: {
            submitForm: function () {
                console.log('Submit Form');
                if (this.isNewStory) {
                    axios.post('/api/stories/', this.form)
                        .then(response => {
                            this.$router.push(response.data.links.self);
                        })
                        .catch(errors => {
                            this.errors = errors.response.data.errors;
                        });
                } else {
                    axios.post('/api/stories/story_parts?story_id=' + this.story.id, this.form)
                        .then(response => {
                            this.$router.push(response.data.links.self);
                        })
                        .catch(errors => {
                            this.errors = errors.response.data.errors;
                        });
                }
                    
            }
        }
    }
</script>

<style lang="scss">
</style>