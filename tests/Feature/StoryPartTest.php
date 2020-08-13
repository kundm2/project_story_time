<?php

namespace Tests\Feature;

use App\Models\StoryPart;
use App\Models\Story;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $this->image_directory = 'public/storage/images';
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        //Storage::cleanDirectory($this->image_directory);
    }
    
    /** @test */
    public function a_story_part_can_be_stored()
    {
        $response = $this->json('POST', '/api/stories/story_parts?story_id=' . $this->story->id, $this->data() );
        $this->assertCount(1, StoryPart::all());
        //$this->assertTwoStoryPartsAreEqual($response, StoryPart::first());
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function a_story_part_can_be_patched()
    {
        $storyPart = factory(StoryPart::class)->create(['created_by' => $this->user->id, 'story_id' => $this->story->id]);
        $response = $this->json('PATCH', '/api/stories/story_parts/' . $storyPart->id . '?story_id=' . $this->story->id, $this->data() );
        $storyPart->refresh();
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function content_and_is_image_is_required()
    {
        collect(['content', 'is_image'])
            ->each(function($field) {
                $response = $this->json('POST', '/api/stories/story_parts?story_id=' . $this->story->id, array_merge($this->data(), [$field => '']));
                $response->assertJsonValidationErrors($field);
                $this->assertCount(0, StoryPart::all());
            });
    }

    /** @test */
    public function url_parameter_story_must_exist()
    {
        $this->withoutExceptionHandling();
        try {
            $response = $this->json('POST', '/api/stories/story_parts', array_merge($this->data(), ['story_id' => '3']));
        } catch (Exception $e) {
            $this->assertStringStartsWith('The given data was invalid', $e->getMessage());
            // TODO: Status is 0 should be 403
            //$this->assertEquals(Response::HTTP_FORBIDDEN, $e->getCode());
            $this->assertCount(0, StoryPart::all());
        }
    }
    
    /** @test */
    public function set_isFinished_after_the_numbers_of_games()
    {   
        for ($i=0; $i < config('global.game_rounds'); $i++) { 
            $response = $this->json('POST', '/api/stories/story_parts?story_id=' . $this->story->id, $this->data() );
        }
        $responseData = json_decode($response->content(), true);
        $this->assertTrue($responseData['data']['isFinished']);
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
                'api_token' => $this->user->api_token
            ];
        } else {
            return [
                // returns an array without
                'content' => $this->faker->sentence(9),
                'is_image' => false,
                'created_by' => $this->user->id,
                'api_token' => $this->user->api_token
            ];
        }
    }
}
