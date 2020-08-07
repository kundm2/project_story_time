<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Story;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
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
    public function a_list_of_all_comments_can_be_retrieved()
    {
        $anotherStory = factory(Story::class)->create(['user_id' => $this->user->id]);
        $comment = factory(Comment::class)->create(['user_id' => $this->user->id, 'story_id' => $this->story->id]);
        $anotherComment = factory(Comment::class)->create(['user_id' => $this->user->id, 'story_id' => $anotherStory->id]);
        $response = $this->get('/api/comments/?api_token=' . $this->user->api_token);
        $this->assertCount(2, json_decode($response->content(), true)['data'] );
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_list_of_all_comments_from_one_story_can_be_retrieved()
    {
        $anotherStory = factory(Story::class)->create(['user_id' => $this->user->id]);
        $comment = factory(Comment::class)->create(['user_id' => $this->user->id, 'story_id' => $this->story->id]);
        $anotherComment = factory(Comment::class)->create(['user_id' => $this->user->id, 'story_id' => $anotherStory->id]);
        $response = $this->json('GET', '/api/comments/?api_token=' . $this->user->api_token . '&story_id=' . $this->story->id);
        //$this->assertCount(1, json_decode($response->content(), true)['data'] );
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function viewing_a_single_comment_is_prohibited()
    {
        $comment = factory(Comment::class)->create(['user_id' => $this->user->id, 'story_id' => $this->story->id]);
        $response = $this->json('GET', '/api/comments' . $comment->id . '?api_token=' . $this->user->api_token);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function a_comment_can_be_stored()
    {
        $response = $this->json('POST', '/api/comments', $this->data());
        $this->assertCount(1, Comment::all());
        $this->assertTwoCommentsAreEqual($response, Comment::first());
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function a_comment_is_required()
    {
        $response = $this->json('POST', '/api/comments', array_merge($this->data(), ['comment' => '']));
        $response->assertJsonValidationErrors('comment');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Comment::all());
    }

    /** @test */
    public function a_comment_can_be_patched()
    {
        $comment = factory(Comment::class)->create(['user_id' => $this->user->id, 'story_id' => $this->story->id]);
        $response = $this->json('PATCH', '/api/comments/' . $comment->id, $this->data());
        $comment->refresh();
        $this->assertTwoCommentsAreEqual($response, $comment);
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_comment_from_another_user_cannot_be_patched()
    {
        $this->withoutExceptionHandling();
        $anotherUser = factory(User::class)->create();
        $comment = factory(Comment::class)->create(['user_id' => $this->user->id, 'story_id' => $this->story->id]);
        try {
            $response = $this->json('PATCH', '/api/comments/' . $comment->id, array_merge($this->data(),
            ['api_token' => $anotherUser->api_token] ));
        } catch (AuthorizationException $ea) {
            $this->assertEquals('You do not own this comment.', $ea->getMessage());
            $this->assertEquals(Response::HTTP_FORBIDDEN, $ea->getCode());
        }
    }

    /** @test */
    public function a_comment_can_be_deleted()
    {
        $comment = factory(Comment::class)->create(['user_id' => $this->user->id, 'story_id' => $this->story->id]);
        $response = $this->json('DELETE', '/api/comments/' . $comment->id, ['api_token' => $this->user->api_token]);
        $comment->refresh();
        $this->assertCount(0, Comment::all());
        $this->assertNotNull($comment->deleted_at);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    private function assertTwoCommentsAreEqual($response, $comment)
    {
        $response->assertJson([
            'data' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'story_id' => $comment->id,
                'user_id' => $comment->user_id
            ],
        ]);
    }

    private function data()
    {
        return [
            'comment' => $this->faker->realText(),
            'story_id' => $this->story->id,
            'api_token' => $this->user->api_token
        ];
    }
}
