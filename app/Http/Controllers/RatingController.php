<?php

namespace App\Http\Controllers;

use App\Http\Resources\RatingResource;
use App\Models\Rating;
use App\Models\Story;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Rating::class);
        if ($request->input('story_id')) {
            $story = Story::firstOrFail($request->input('story_id'));
            return RatingResource::collection($story->ratings()->paginate(config('global.pagination_records')) );
        } else {
            return RatingResource::collection(Rating::paginate(config('global.pagination_records')));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Rating::class);
        $rating = request()->user()->ratings()->create($this->validateRatingData());
        return (new RatingResource($rating))->response(Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rating $rating)
    {
        $this->authorize('update', $rating);
        $rating->update($this->validateRatingData());
        return new RatingResource($rating);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rating $rating)
    {
        $this->authorize('delete', $rating);
        $rating->delete();
        return response([], Response::HTTP_NO_CONTENT);
    }

    public function validateRatingData()
    {
        return request()->validate([
            'rating' => 'required|in:1,1.5,2.0,2.5,3,3.5,4,4.5,5',
            'story_id' => 'required|exists:App\Models\Story,id',
        ]);
    }
}
