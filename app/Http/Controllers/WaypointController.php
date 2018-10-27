<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Point;
use App\Path;

class WaypointController extends Controller
{
    public function index()
    {
        $points = Point::query()
            ->select('id', 'name', 'latitude', 'longitude')
            ->where('type', 'WAYPOINT')
            ->get();

        return view('waypoint.index', compact('points'));
    }

    public function delete(Point $waypoint)
    {
        DB::transaction(function() use($waypoint) {
            Path::where('point_a_id', $waypoint->id)
                ->orWhere('point_b_id', $waypoint->id)
                ->delete();

            $waypoint->delete();
        });

        return back()
            ->with('message.success', __('messages.delete.success'));
    }
}
