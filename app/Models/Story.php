<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;

class Story extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stories';

    use SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parts()
    {
        return $this->hasMany(StoryPart::class);
    }

    public function path()
    {
        return '/stories/' . $this->id;
    }

    public function full_path()
    {
        return URL::to('/stories/' . $this->id);
    }
}
