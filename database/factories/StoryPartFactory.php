<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Story;
use App\Models\StoryPart;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(StoryPart::class, function (Faker $faker) {
    $is_image = $faker->boolean();
    if ($is_image) {
        return [
            'content' => $faker->text(),
            'is_image' => $is_image,
            'created_by' => factory(User::class),
            'story_id' => factory(Story::class),
        ];
    } else {
        return [
            'content' => $faker->image('public/storage/images', 500, 500, null, false),
            'is_image' => $is_image,
            'created_by' => factory(User::class),
            'story_id' => factory(Story::class),
        ];
    }
});
