<?php

use Faker\Generator as Faker;

$factory->define(App\Site::class, function (Faker $faker) {
    return [
        'visitor_count' => rand(30000, 40000),
        'fee' => rand(5, 10) * 10000,
        'facility_count' => rand(5, 8),
        'point_id' => factory(App\Point::class)->create(['type' => 'SITE'])->id
    ];
});
