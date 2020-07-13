<?php

namespace Tests\Feature;

use App\Models\StoryPart;
use App\Models\Story;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class StoryPartTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    protected $user;
    protected $story;
    protected $image_directory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->story = factory(Story::class)->create();
        $this->image_directory = storage_path('images');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Storage::cleanDirectory($this->image_directory);
    }
    

    /** @test */
    public function a_rating_can_be_stored()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/stories/' . $this->story->id . '/story_parts', $this->data());
        $this->assertCount(1, StoryPart::all());
        $this->assertTwoStoryPartsAreEqual($response, StoryPart::first());
        $response->assertStatus(Response::HTTP_CREATED);
    }

    private function assertTwoStoryPartsAreEqual($response, $storyPart)
    {
        $response->assertJson([
            'data' => [
                'id' => $storyPart->id,
                'content' => $storyPart->content,
                'is_image' => $storyPart->is_image,
                'created_by' => $storyPart->user_id,
                'story_id' => $storyPart->story_id
            ],
        ]);
    }

    private function data($is_image = false)
    {
        if ($is_image) {
            return [
                // returns an array with an image
                'content' => $this->faker->image($this->image_directory, 500, 500, null, false),
                'is_image' => true,
                'created_by' => $this->user->id,
                'story_id' => $this->story->id,
                'api_token' => $this->user->api_token
            ];
        } else {
            return [
                // returns an array without
                'content' => $this->faker->sentence(9),
                'is_image' => false,
                'created_by' => $this->user->id,
                'story_id' => $this->story->id,
                'api_token' => $this->user->api_token
            ];
        }
    }
}
