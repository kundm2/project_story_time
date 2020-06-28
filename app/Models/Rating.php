<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;

class Rating extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rating', 'story_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function path()
    {
        return '/ratings/' . $this->id;
    }

    public function full_path()
    {
        return URL::to('/ratings/' . $this->id);
    }
}
