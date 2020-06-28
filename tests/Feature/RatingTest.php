<?php

namespace Tests\Feature;

use App\Models\Rating;
use App\Models\Story;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class RatingTest extends TestCase
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

    /** @test */
    public function a_list_of_all_ratings_can_be_retrieved()
    {
        $anotherStory = factory(Story::class)->create(['user_id' => $this->user->id]);
        $rating = factory(Rating::class)->create(['user_id' => $this->user->id, 'story_id' => $this->story->id]);
        $anotherRating = factory(Rating::class)->create(['user_id' => $this->user->id, 'story_id' => $anotherStory->id]);
        $response = $this->get('/api/ratings/?api_token=' . $this->user->api_token);
        $this->assertCount(2, json_decode($response->content(), true)['data'] );
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_list_of_all_ratings_from_one_story_can_be_retrieved()
    {
        $anotherStory = factory(Story::class)->create(['user_id' => $this->user->id]);
        $rating = factory(Rating::class)->create(['user_id' => $this->user->id, 'story_id' => $this->story->id]);
        $anotherRating = factory(Rating::class)->create(['user_id' => $this->user->id, 'story_id' => $anotherStory->id]);
        $response = $this->get('/api/ratings/?api_token=' . $this->user->api_token . '&story_id=' . $this->story->id);
        $this->assertCount(1, json_decode($response->content(), true)['data'] );
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function viewing_a_single_rating_is_prohibited()
    {
        $rating = factory(Rating::class)->create(['user_id' => $this->user->id, 'story_id' => $this->story->id]);
        $response = $this->get('/api/ratings/' . $rating->id . '?api_token=' . $this->user->api_token);
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /** @test */
    public function a_rating_can_be_stored()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/ratings', $this->data());
        $this->assertCount(1, Rating::all());
        $this->assertTwoRatingsAreEqual($response, Rating::first());
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function a_rating_is_required()
    {
        $response = $this->post('/api/ratings', array_merge($this->data(), ['rating' => null]) );
        $response->assertJsonValidationErrors('rating');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Rating::all());
    }

    /** @test */
    public function a_rating_has_to_be_an_integer_or_a_half_number_between_one_and_five()
    {
        $response = $this->post('/api/ratings', array_merge($this->data(), ['rating' => 1.25]) );
        $response->assertJsonValidationErrors('rating');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Rating::all());

        $response = $this->post('/api/ratings', array_merge($this->data(), ['rating' => 12.5]) );
        $response->assertJsonValidationErrors('rating');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Rating::all());

        $response = $this->post('/api/ratings', array_merge($this->data(), ['rating' => 6]) );
        $response->assertJsonValidationErrors('rating');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Rating::all());
    }

    /** @test */
    public function a_rating_can_be_patched()
    {
        $rating = factory(Rating::class)->create(['user_id' => $this->user->id, 'story_id' => $this->story->id]);
        $response = $this->patch('/api/ratings/' . $rating->id, $this->data() );
        $rating->refresh();
        $this->assertTwoRatingsAreEqual($response, $rating);
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_rating_from_another_user_cannot_be_patched()
    {
        $this->withoutExceptionHandling();
        $anotherUser = factory(User::class)->create();
        $rating = factory(Rating::class)->create(['user_id' => $this->user->id, 'story_id' => $this->story->id]);
        try {
            $response = $this->patch('/api/ratings/' . $rating->id, array_merge($this->data(),
            ['api_token' => $anotherUser->api_token] ));
        } catch (AuthorizationException $ea) {
            $this->assertEquals('You do not own this rating.', $ea->getMessage());
            $this->assertEquals(Response::HTTP_FORBIDDEN, $ea->getCode());
        }
    }

    /** @test */
    public function a_rating_can_be_deleted()
    {
        $rating = factory(Rating::class)->create(['user_id' => $this->user->id, 'story_id' => $this->story->id]);
        $response = $this->delete('/api/ratings/' . $rating->id, ['api_token' => $this->user->api_token]);
        $rating->refresh();
        $this->assertCount(0, Rating::all());
        $this->assertNotNull($rating->deleted_at);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    private function assertTwoRatingsAreEqual($response, $rating)
    {
        $response->assertJson([
            'data' => [
                'id' => $rating->id,
                'rating' => $rating->rating,
                'story_id' => $rating->story_id,
                'user_id' => $rating->user_id
            ],
        ]);
    }

    private function data()
    {
        return [
            'rating' => $this->faker->randomElement([1.0, 1.5, 2.0, 2.5, 3.0, 3.5, 4.0, 4.5, 5.0]),
            'story_id' => $this->story->id,
            'api_token' => $this->user->api_token
        ];
    }
}
