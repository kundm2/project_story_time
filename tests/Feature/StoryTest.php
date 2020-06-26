<?php

namespace Tests\Feature;

use App\Models\Story;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StoryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function a_story_can_be_stored_function()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/stories', $this->data());
        $this->assertCount(1, Story::all());
        $this->assertTwoStoriesAreEqual($response, Story::first());
        $response->assertStatus(Response::HTTP_CREATED);

    }

    /** @test */
    public function a_story_can_be_retrieved_function()
    {
        $story = factory(Story::class)->create(['user_id' => $this->user->id]);
        $response = $this->get('/api/stories/' . $story->id . '?api_token=' . $this->user->api_token);
        $this->assertTwoStoriesAreEqual($response, $story);
        $this->assertCount(1, Story::all());

        $response->assertStatus(Response::HTTP_OK);
    }

    private function assertTwoStoriesAreEqual($response, $story)
    {
        $response->assertJson([
            'id' => $story->id,
            'language' => $story->language,
            'user_id' => $story->user_id
        ]);
    }

    private function data()
    {
        return [
            'language' => 'en',
            'api_token' => $this->user->api_token
        ];
    }
}
