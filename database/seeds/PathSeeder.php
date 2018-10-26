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
                    'distance' => $this->haversine_distance($points[$i]['latitude'], $points[$i]['longitude'], $points[$j]['latitude'], $points[$j]['longitude'])
                ]);
            }
        }
    }

    public function haversine_distance($lat_a, $lng_a, $lat_b, $lng_b, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($lat_a);
        $lonFrom = deg2rad($lng_a);
        $latTo = deg2rad($lat_b);
        $lonTo = deg2rad($lng_b);
      
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
      
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}
