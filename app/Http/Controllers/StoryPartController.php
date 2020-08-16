<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoryPartRequest;
use App\Http\Resources\StoryResource;
use App\Models\Story;
use App\Models\StoryPart;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

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

        // If image uploaded, it has to be saved first 
        response($story);
        if($request->is_image) {
            $image_64 = $request->input('content'); //your base64 encoded data
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1]; // png
            $replace = substr($image_64, 0, strpos($image_64, ',')+1);             
            $image = str_replace($replace, '', $image_64); 
            $image = str_replace(' ', '+', $image); 

            $dt = new DateTime();
            $imageName = $dt->format('Y-m-d-H-i-s') . '-' . Str::random(60) . '.' . $extension;
          
            Storage::disk('images')->put($imageName, base64_decode($image));
            StoryPart::create([
                'content' => $imageName,
                'is_image' => $request->is_image,
                'created_by' => Auth::user()->id,
                'story_id' => $request->story_id
            ]);
        }
        else {
            $story->parts()->create([
                'content' => $request->content,
                'is_image' => $request->is_image,
                'created_by' => Auth::user()->id,
                'story_id' => $request->story_id
            ]);
        }
        
        
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
