<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Point;
use App\Path;

class SiteController extends Controller
{
    public function index() {

        $points = Point::query()
            ->isSite()
            ->select('id', 'name', 'type')
            ->with('site:id,point_id,visitor_count,fee,facility_count')
            ->get();

        return view('site.index', compact('points'));
    }

    public function create()
    {
        $points = Point::query()
            ->select('id', 'name', 'latitude', 'longitude', 'type')
            ->with('paths_from:point_a_id,point_b_id')
            ->get()
            ->keyBy('id');

        return view('site.create', compact('points'));
    }

    public function map()
    {
        $points = Point::query()
            ->select('id', 'name', 'latitude', 'longitude', 'type')
            ->with('paths_to:point_a_id,point_b_id')
            ->with('paths_from:point_a_id,point_b_id')
            ->get()
            ->map(function ($point) {
                
                $point->paths = collect([]);

                $point->paths_to->each(function ($path) use($point) {
                    $point->paths->push([
                        'id' => $path->point_a_id,
                    ]);
                });

                $point->paths_from->each(function ($path) use($point) {
                    $point->paths->push([
                        'id' => $path->point_b_id,
                    ]);
                });

                unset($point->paths_to);
                unset($point->paths_from);

                return $point;
            })
            ->keyBy('id');

        return view('site.map', compact('points'));
    }
}
