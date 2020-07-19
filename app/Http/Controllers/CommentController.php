<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Story;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    /**
     * Display a listing of stories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Comment::class);
        if ($request->input('story_id')) {
            $story = Story::firstOrFail($request->input('story_id'));
            return CommentResource::collection($story->comments()->paginate(config('global.pagination_records')) );
        } else {
            return CommentResource::collection(Comment::paginate(config('global.pagination_records')));
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
        $this->authorize('create', Comment::class);
        $comment = request()->user()->comments()->create($this->validateCommentData());
        return response(new CommentResource($comment), Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);
        $comment->update($this->validateCommentData());
        return response(new CommentResource($comment), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return response([], Response::HTTP_NO_CONTENT);
    }

    public function validateCommentData()
    {
        return request()->validate([
            'comment' => 'required',
            'story_id' => 'required|exists:App\Models\Story,id',
        ]);
    }
}
