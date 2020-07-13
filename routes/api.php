<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:api')->group( function () {
    Route::resource('stories', 'StoryController');
    Route::resource('comments', 'CommentController', ['except' => [ 'show' ]] );
    Route::resource('ratings', 'RatingController', ['except' => [ 'show' ]] );
    Route::resource('stories/{id}/story_parts', 'StoryPartController', ['except' => [ 'show' ]] );
});
