<?php

use Illuminate\Database\Seeder;
use App\Point;
use App\Path;

class ExperimentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $point_data = [
            ['name' => 'A', 'latitude' => 0, 'longitude' => 0, 'type' => 'WAYPOINT'], // 0
            ['name' => 'B', 'latitude' => 0, 'longitude' => 1, 'type' => 'WAYPOINT'], // 1
            ['name' => 'C', 'latitude' => 0.5, 'longitude' => 0.5, 'type' => 'WAYPOINT'], // 2
            ['name' => 'D', 'latitude' => 1, 'longitude' => 1, 'type' => 'WAYPOINT'], // 3
            ['name' => 'E', 'latitude' => 1, 'longitude' => 0, 'type' => 'WAYPOINT'], // 4
            ['name' => 'F', 'latitude' => 2, 'longitude' => 1, 'type' => 'SITE'], // 5
            ['name' => 'G', 'latitude' => 2, 'longitude' => 0, 'type' => 'WAYPOINT'], // 6
        ];
        
        foreach ($point_data as $point) {
            Point::create($point);
        }

        $path_data = [
            ['point_a_id' => 1, 'point_b_id' => 2, 'distance' => 4], // A, B
            ['point_a_id' => 1, 'point_b_id' => 3, 'distance' => 3], // A, C
            ['point_a_id' => 1, 'point_b_id' => 5, 'distance' => 7], // A, E

            ['point_a_id' => 2, 'point_b_id' => 4, 'distance' => 5], // B, D
            ['point_a_id' => 2, 'point_b_id' => 3, 'distance' => 6], // B, C
            ['point_a_id' => 3, 'point_b_id' => 4, 'distance' => 11], // C, D

            ['point_a_id' => 3, 'point_b_id' => 5, 'distance' => 8], // C, E
            ['point_a_id' => 4, 'point_b_id' => 5, 'distance' => 2], // D, E
            ['point_a_id' => 4, 'point_b_id' => 6, 'distance' => 2], // D, F

            ['point_a_id' => 4, 'point_b_id' => 7, 'distance' => 10], // D, G
            ['point_a_id' => 5, 'point_b_id' => 7, 'distance' => 5], // E, G
            ['point_a_id' => 6, 'point_b_id' => 7, 'distance' => 3], // F, G
        ];

        foreach ($path_data as $path) {
            Path::create($path);
        }
    }
}
