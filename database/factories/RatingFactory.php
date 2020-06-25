<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Rating;
use Faker\Generator as Faker;

$factory->define(Rating::class, function (Faker $faker) {
    return [
        'rating' => $faker->randomElement([1.0, 1.5, 2.0, 2.5, 3.0, 3.5, 4.0, 4.5, 5.0]),
        'user_id' => factory(User::class),
        'story_id' => factory(Story::class),
    ];
});
