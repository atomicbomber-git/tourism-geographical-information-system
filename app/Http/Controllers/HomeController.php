<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slide;
use App\Point;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slides = Slide::select('id', 'name', 'description')->get();
        $points = Point::query()
            ->select('id', 'name', 'latitude', 'longitude', 'type')
            ->with('paths_from:point_a_id,point_b_id')
            ->get()
            ->keyBy('id');

        return view('home', compact('slides', 'points'));
    }
}
