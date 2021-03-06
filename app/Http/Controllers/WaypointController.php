<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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

    public function create()
    {
        $points = Point::query()
            ->select('id', 'name', 'latitude', 'longitude')
            ->with('paths_from:point_a_id,point_b_id')
            ->get()
            ->keyBy('id');

        return view('waypoint.create', compact('points'));
    }

    public function store()
    {
        $data = $this->validate(request(), [
            'name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        Point::create(array_merge($data, [
            'type' => 'WAYPOINT'
        ]));

        return [
            'status' => 'success',
        ];
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

    public function edit(Point $waypoint)
    {
        $points = Point::query()
            ->select('id', 'name', 'latitude', 'longitude')
            ->with('paths_from:point_a_id,point_b_id')
            ->get()
            ->keyBy('id');

        return view('waypoint.edit', compact('waypoint', 'points'));
    }

    public function update(Point $waypoint)
    {
        $data = $this->validate(request(), [
            'name' => ['required', 'string', Rule::unique('points')->ignore($waypoint->id)],
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $waypoint->update($data);

        return [
            'success' => true
        ];
    }
}
