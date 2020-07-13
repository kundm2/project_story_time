<?php

namespace App\Http\Controllers;

use App\Http\Resources\StoryResource;
use App\Models\Story;
use App\Models\StoryPart;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoryPartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $this->authorize('create', StoryPart::class);
        $story = Story::findOrFail($id);
        $story->parts()->create($this->validateStoryPartData());
        return (new StoryResource($story))->response(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StoryPart  $storyPart
     * @return \Illuminate\Http\Response
     */
    public function show(StoryPart $storyPart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StoryPart  $storyPart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoryPart $storyPart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoryPart  $storyPart
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoryPart $storyPart)
    {
        //
    }

    public function validateStoryPartData()
    {
        return request()->validate([
            'content' => 'required',
            'is_image' => 'required',
            'created_by' => 'required|exists:App\Models\User,id',
        ]);
    }
}
