<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoryPartRequest;
use App\Http\Resources\StoryResource;
use App\Models\Story;
use App\Models\StoryPart;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoryPartController extends Controller
{
    /**
     * Store a newly created story parts in storage.
     *
     * @param  \App\Http\Requests\StoryPartRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoryPartRequest $request)
    {
        $this->authorize('create', StoryPart::class);
        $story = Story::findOrFail(request()->story_id);
        $story->parts()->create($this->validateStoryPartData());
        if ($story->parts()->count() == config('global.game_rounds')) {
            $story->isFinished = true;
            $story->save();
        }
        return response(new StoryResource($story), Response::HTTP_CREATED);
    }

    /**
     * Update the specified story parts in storage.
     *
     * @param  \App\Http\Requests\StoryPartRequest  $request
     * @param  \App\Models\StoryPart  $storyPart
     * @return \Illuminate\Http\Response
     */
    public function update(StoryPartRequest $request, StoryPart $storyPart)
    {
        $this->authorize('update', $storyPart);
        $storyPart->update($this->validateStoryPartData());
        $story = Story::findOrFail($storyPart->story_id);
        return response(new StoryResource($story), Response::HTTP_OK);
    }

    /**
     * Remove the specified story parts from storage.
     *
     * @param  \App\Models\StoryPart  $storyPart
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoryPart $storyPart)
    {
        $this->authorize('delete', $storyPart);
        $storyPart->delete();
        return response([], Response::HTTP_NO_CONTENT);
    }

    public function validateStoryPartData()
    {
        return request()->validate([
            'content' => 'required',
            'is_image' => 'required',
            'created_by' => 'required|exists:App\Models\User,id',
            'story_id' => 'required|exists:App\Models\Story,id'
        ]);
    }
}
