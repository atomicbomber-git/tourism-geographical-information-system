<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Path;
use App\Point;

class PathController extends Controller
{
    public function index()
    {
        $paths = Path::query()
            ->select('id', 'point_a_id', 'point_b_id', 'distance')
            ->with('point_to:id,name', 'point_from:id,name')
            ->get();

        return view('path.index', compact('paths'));
    }

    public function create()
    {
        $points = Point::query()
            ->select('id', 'latitude', 'longitude', 'name')
            ->with('paths_from:point_a_id,point_b_id', 'paths_to:point_a_id,point_b_id')
            ->get()
            ->keyBy('id');

        return view('path.create', compact('points'));
    }
}
