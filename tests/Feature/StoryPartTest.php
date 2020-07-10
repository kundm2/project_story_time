<?php

namespace Tests\Feature;

use App\Models\StoryPart;
use App\Models\Story;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class StoryPartTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    protected $user;
    protected $story;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->story = factory(Story::class)->create();
    }

    private function assertTwoRatingsAreEqual($response, $storyPart)
    {
        $response->assertJson([
            'data' => [
                'id' => $storyPart->id,
                'content' => $storyPart->content,
                'is_image' => $storyPart->is_image,
                'story_id' => $storyPart->story_id,
                'user_id' => $storyPart->user_id
            ],
        ]);
    }

    /** @test */
    public function viewing_a_single_story_part_is_prohibited()
    {
        $rating = factory(StoryPart::class)->create(['created_by' => $this->user->id, 'story_id' => $this->story->id]);
        $response = $this->get('/api/story_parts/' . $rating->id . '?api_token=' . $this->user->api_token);
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /** @test */
    public function a_rating_can_be_stored()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/story_parts', $this->data());
        $this->assertCount(1, StoryPart::all());
        $this->assertTwoRatingsAreEqual($response, StoryPart::first());
        $response->assertStatus(Response::HTTP_CREATED);
    }

    private function data($is_image = false)
    {
        if ($is_image) {
            return [
                // returns an array with an image
                'content' => '',
                'is_image' => true,
                'created_by' => $this->user->id,
                'story_id' => $this->story->id,
                'api_token' => $this->user->api_token
            ];
        } else {
            return [
                'content' => $this->faker->sentence(9),
                'is_image' => false,
                'created_by' => $this->user->id,
                'story_id' => $this->story->id,
                'api_token' => $this->user->api_token
            ];
        }
    }
}
