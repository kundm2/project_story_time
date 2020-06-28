<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Story;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Story::class, function (Faker $faker) {
    return [
        'language' => $faker->languageCode,
        'user_id' => factory(User::class),
    ];
});
