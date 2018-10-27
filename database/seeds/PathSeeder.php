<?php

use Illuminate\Database\Seeder;
use App\Point;
use App\Path;

class PathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $points = Point::select('id', 'latitude', 'longitude', 'type')
            ->orderBy('id')
            ->get()
            ->toArray();

        for ($i = 0; $i < count($points); ++$i) {
            for ($j = $i + 1; $j < count($points); ++$j) {
                
                if (rand(0, 5) != 0) { continue; }

                if ($points[$i]['type'] == 'SITE' && $points[$j]['type'] == 'SITE') {
                    continue;
                }

                Path::create([
                    'point_a_id' => $points[$i]['id'],
                    'point_b_id' => $points[$j]['id'],
                ]);
            }
        }
    }
}
