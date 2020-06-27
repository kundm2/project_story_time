<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;

class StoryPart extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    // protected $table = 'story_parts';

    protected $guarded = [];

    use SoftDeletes;



    public function path()
    {
        return '/story_parts/' . $this->id;
    }

    public function full_path()
    {
        return URL::to('/story_parts/' . $this->id);
    }
}
