<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Point;
use App\Path;

class SiteController extends Controller
{
    public function map()
    {
        $points = Point::query()
            ->select('id', 'name', 'latitude', 'longitude', 'type')
            ->with('paths_to:point_a_id,point_b_id,distance')
            ->with('paths_from:point_a_id,point_b_id,distance')
            ->get()
            ->map(function ($point) {
                
                $point->paths = collect([]);

                $point->paths_to->each(function ($path) use($point) {
                    $point->paths->push([
                        'id' => $path->point_a_id,
                        'distance' => $path->distance
                    ]);
                });

                $point->paths_from->each(function ($path) use($point) {
                    $point->paths->push([
                        'id' => $path->point_b_id,
                        'distance' => $path->distance
                    ]);
                });

                unset($point->paths_to);
                unset($point->paths_from);

                return $point;
            })
            ->keyBy('id');

        return view('site.index', compact('points'));
    }
}