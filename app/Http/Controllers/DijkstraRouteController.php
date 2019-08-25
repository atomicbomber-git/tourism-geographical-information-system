<?php

namespace App\Http\Controllers;

use App\Point;
use Illuminate\Http\Request;

class DijkstraRouteController extends Controller
{
    public function show(Request $request)
    {
        $points = Point::query()
            ->select("id", "name", "latitude", "longitude")
            ->with("paths_from:id,point_a_id,point_b_id")
            ->get();

        return view("dijkstra-route.show", compact("points"));
    }
}
