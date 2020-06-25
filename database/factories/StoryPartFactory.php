<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Story;
use App\Models\StoryPart;
use Faker\Generator as Faker;

$factory->define(StoryPart::class, function (Faker $faker) {
    return [
        'content' => $faker->text(),
        'is_image' => $faker->boolean(),
        'created_by' => factory(User::class),
        'story_id' => factory(Story::class),
    ];
});
