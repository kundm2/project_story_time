<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoryRequest;
use App\Http\Resources\StoryResource;
use App\Models\User;
use App\Models\Story;
use App\Models\StoryPart;
use Dotenv\Result\Result;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class StoryController extends Controller
{
    /**
     * Display a listing of stories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Story::class);
        if ($request->input('play')) {
            // Get a story where the user hasn't participated yet.
            $userID = request()->user()->id;
            $story = Story::where([['user_id', '<>', $userID], ['isFinished' , '=', null]])->inRandomOrder()->first();
            return response(new StoryResource($story), Response::HTTP_OK);
        } else if ($request->input('id')) {
            return StoryResource::collection(request()->user()->stories()->orderBy('updated_at', 'desc')->paginate(config('global.pagination_records')) );
        } else {
            return StoryResource::collection(Story::paginate(config('global.pagination_records')));
        }
    }

    /**
     * Store a newly created story in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Requests\StoryRequest
     */
    public function store(StoryRequest $request)
    {
        $this->authorize('create', Story::class);
        $story = request()->user()->stories()->create($this->validateStoryData());
        $story->parts()->create(['content' => $request->content, 'created_by' => request()->user()->id]);
        return response(new StoryResource($story), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Story  $story
     * @return \App\Http\Requests\StoryRequest
     */
    public function show(Story $story)
    {
        $this->authorize('view', $story);
        return response(new StoryResource($story), Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Story $story)
    {
        $this->authorize('update', $story);
        $story->update($this->validateStoryData());
        return response(new StoryResource($story), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Story  $story
     * @return \App\Http\Requests\StoryRequest
     */
    public function destroy(Story $story)
    {
        $this->authorize('delete', $story);
        $story->delete();
        return response([], Response::HTTP_NO_CONTENT);
    }

    public function validateStoryData()
    {
        return request()->validate([
            'language' => 'required|size:2',
        ]);
    }
}
