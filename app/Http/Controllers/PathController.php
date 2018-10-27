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
            ->select('id', 'point_a_id', 'point_b_id')
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

    public function store()
    {
        $data = $this->validate(request(), [
            'start_point_id' => 'required|numeric',
            'end_point_id' => 'required|numeric'
        ]);

        $path = new Path;

        if ($data['start_point_id'] > $data['end_point_id']) {
            $path->point_a_id = $data['start_point_id'];
            $path->point_b_id = $data['end_point_id'];
        }
        else {
            $path->point_a_id = $data['end_point_id'];
            $path->point_b_id = $data['start_point_id'];
        }

        $path->save();

        return [
            'success' => true
        ];
    }

    public function delete(Path $path)
    {
        $path->delete();

        return back()
            ->with('message.success', __('messages.delete.success'));
    }
}
