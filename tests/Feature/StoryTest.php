<?php

namespace Tests\Feature;

use App\Models\Story;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
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
    public function a_list_of_all_stories_can_be_retrieved()
    {
        $story = factory(Story::class)->create(['user_id' => $this->user->id]);
        $anotherUser = factory(User::class)->create();
        $anotherStory = factory(Story::class)->create(['user_id' => $anotherUser->id]);
        $response = $this->get('/api/stories/?api_token=' . $this->user->api_token);
        $this->assertCount(2, json_decode($response->content(), true)['data'] );
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_list_of_all_stories_from_one_user_can_be_retrieved()
    {
        $story = factory(Story::class)->create(['user_id' => $this->user->id]);
        $anotherUser = factory(User::class)->create();
        $anotherStory = factory(Story::class)->create(['user_id' => $anotherUser->id]);
        $response = $this->get('/api/stories/?api_token=' . $this->user->api_token . '&id=1');
        $this->assertCount(1, json_decode($response->content(), true)['data'] );
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_list_of_stories_on_second_page_can_be_retrieved()
    {
        for ($i=0; $i < 20; $i++) {
            factory(Story::class)->create(['user_id' => $this->user->id]);
        }
        $response = $this->get('/api/stories/?api_token=' . $this->user->api_token . '&id=1s&page=2');
        $this->assertCount(5, json_decode($response->content(), true)['data'] );
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_story_can_be_stored()
    {
        $response = $this->post('/api/stories', $this->data());
        $this->assertCount(1, Story::all());
        $this->assertTwoStoriesAreEqual($response, Story::first());
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function a_language_code_needs_two_chars_long()
    {
        $response = $this->post('/api/stories', array_merge($this->data(), ['language' => 'english']));
        $response->assertJsonValidationErrors('language');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Story::all());

        $response = $this->post('/api/stories', array_merge($this->data(), ['language' => '']));
        $response->assertJsonValidationErrors('language');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Story::all());
    }

    /** @test */
    public function a_story_can_be_retrieved()
    {
        $story = factory(Story::class)->create(['user_id' => $this->user->id]);
        $response = $this->get('/api/stories/' . $story->id . '?api_token=' . $this->user->api_token);
        $this->assertTwoStoriesAreEqual($response, $story);
        $this->assertCount(1, Story::all());
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_story_can_be_patched()
    {
        $story = factory(Story::class)->create(['user_id' => $this->user->id]);
        $response = $this->patch('/api/stories/' . $story->id, $this->data() );
        $story->refresh();
        $this->assertTwoStoriesAreEqual($response, $story);
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_story_from_another_user_cannot_be_patched()
    {
        $this->withoutExceptionHandling();
        $story = factory(Story::class)->create(['user_id' => $this->user->id]);
        $anotherUser = factory(User::class)->create();
        try {
            $response = $this->patch('/api/stories/' . $story->id, array_merge($this->data(),
            ['api_token' => $anotherUser->api_token] ));
        } catch (AuthorizationException $ea) {
            $this->assertEquals('You do not own this story.', $ea->getMessage());
            $this->assertEquals(Response::HTTP_FORBIDDEN, $ea->getCode());
        }
    }

    /** @test */
    public function a_story_can_be_deleted()
    {
        $story = factory(Story::class)->create(['user_id' => $this->user->id]);
        $response = $this->delete('/api/stories/' . $story->id, ['api_token' => $this->user->api_token]);
        $story->refresh();
        $this->assertCount(0, Story::all());
        $this->assertNotNull($story->deleted_at);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function a_story_from_another_user_cannot_be_deleted()
    {
        $this->withoutExceptionHandling();
        $anotherUser = factory(User::class)->create();
        $story = factory(Story::class)->create(['user_id' => $this->user->id]);
        try {
            $response = $this->delete('/api/stories/' . $story->id, ['api_token' => $anotherUser->api_token]);
        } catch (AuthorizationException $ea) {
            $this->assertEquals('You do not own this story.', $ea->getMessage());
            $this->assertEquals(Response::HTTP_FORBIDDEN, $ea->getCode());
        }
    }

    private function assertTwoStoriesAreEqual($response, $story)
    {
        $response->assertJson([
            'data' => [
                'id' => $story->id,
                'language' => $story->language,
                'user_id' => $story->user_id
            ],
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
