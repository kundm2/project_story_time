<?php

namespace Tests\Feature;

use App\Models\Story;
use App\Models\StoryPart;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StoryTest extends TestCase
{
    use WithFaker;
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
        $response = $this->json('GET', '/api/stories/?api_token=' . $this->user->api_token);
        $this->assertCount(2, json_decode($response->content(), true)['data'] );
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_list_of_all_stories_from_one_user_can_be_retrieved()
    {
        $story = factory(Story::class)->create(['user_id' => $this->user->id]);
        $anotherUser = factory(User::class)->create();
        $anotherStory = factory(Story::class)->create(['user_id' => $anotherUser->id]);
        $response = $this->json('GET', '/api/stories/?api_token=' . $this->user->api_token . '&id=1');
        $this->assertCount(1, json_decode($response->content(), true)['data'] );
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_list_of_stories_on_second_page_can_be_retrieved()
    {
    factory(Story::class, 20)->create(['user_id' => $this->user->id]);
        $response = $this->json('GET', '/api/stories/?api_token=' . $this->user->api_token . '&id=1s&page=2');
        $this->assertCount(5, json_decode($response->content(), true)['data'] );
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_story_can_be_stored()
    {
        $response = $this->json('POST', '/api/stories', $this->newStoryData());
        $this->assertCount(1, Story::all());
        $this->assertCount(1, StoryPart::all());
        $this->assertTwoStoriesAreEqual($response, Story::first());
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function a_language_code_needs_two_chars_long()
    {
        $response = $this->json('POST', '/api/stories', array_merge($this->newStoryData(), ['language' => 'english']));
        $response->assertJsonValidationErrors('language');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Story::all());

        $response = $this->json('POST', '/api/stories', array_merge($this->newStoryData(), ['language' => '']));
        $response->assertJsonValidationErrors('language');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Story::all());
    }

    /** @test */
    public function the_content_field_is_required()
    {
        $response = $this->json('POST', '/api/stories', array_merge($this->newStoryData(), ['content' => '']));
        $response->assertJsonValidationErrors('content');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Story::all());
    }

    /** @test */
    public function a_story_can_be_retrieved()
    {
        $story = factory(Story::class)->create(['user_id' => $this->user->id]);
        $response = $this->json('GET', '/api/stories/' . $story->id . '?api_token=' . $this->user->api_token);
        $this->assertTwoStoriesAreEqual($response, $story);
        $this->assertCount(1, Story::all());
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_story_for_a_new_game_can_be_retrieved()
    {
        $this->withoutExceptionHandling();
        $this->json('POST', '/api/stories', $this->newStoryData());
        $anotherUser = factory(User::class)->create();
        factory(Story::class)->create();
        factory(Story::class)->create(['user_id' => $anotherUser->id]);
        $response = $this->json('GET', '/api/stories/?play=1&api_token=' . $this->user->api_token);
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_story_can_be_patched()
    {
        $story = factory(Story::class)->create(['user_id' => $this->user->id]);
        $response = $this->json('PATCH', '/api/stories/' . $story->id, $this->storyData() );
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
            $response = $this->json('PATCH', '/api/stories/' . $story->id, array_merge($this->storyData(),
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
        $response = $this->json('DELETE', '/api/stories/' . $story->id, ['api_token' => $this->user->api_token]);
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
            $response = $this->json('DELETE', '/api/stories/' . $story->id, ['api_token' => $anotherUser->api_token]);
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

    private function storyData()
    {
        return [
            'language' => 'en',
            'api_token' => $this->user->api_token
        ];
    }

    private function newStoryData() {
        return [
            'content' => $this->faker->sentence(9),
            'language' => 'en',
            'api_token' => $this->user->api_token
        ];
    }
}
