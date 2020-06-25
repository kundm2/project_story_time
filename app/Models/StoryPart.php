<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoryPart extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    // protected $table = 'story_parts';

    use SoftDeletes;
}
