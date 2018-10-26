<?php

use Faker\Generator as Faker;

$factory->define(App\Point::class, function (Faker $faker) {
    return [
        'name' => $faker->streetName,
        'latitude' => rand(-200, 200) / 1000 + 0.8192948,
        'longitude' => rand(-200, 200) / 1000 + 109.4806557,
        'type' => $faker->randomElement(array_keys(App\Point::TYPES ))
    ];
});
