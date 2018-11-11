<?php

use Faker\Generator as Faker;

$factory->define(App\Site::class, function (Faker $faker) {
    return [
        'visitor_count' => rand(30000, 50000),
        'fee' => rand(5, 25) * 10000,
        'facility_count' => rand(2, 12),
        'point_id' => factory(App\Point::class)->create(['type' => 'SITE'])->id,
        'description' => $faker->realText($maxNbChars = 600, $indexSize = 2)
    ];
});
