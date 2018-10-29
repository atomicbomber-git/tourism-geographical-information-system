<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Point;
use App\Path;
use App\Site;

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

    public function store()
    {
        $data = $this->validate(request(), [
            'name' => 'required|string|unique:points',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'visitor_count' => 'required|numeric',
            'fee' => 'required|numeric',
            'facility_count' => 'required|numeric'
        ]);

        DB::transaction(function() use($data) {
            $point = Point::create([
                'name' => $data['name'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'type' => 'SITE'
            ]);

            Site::create([
                'point_id' => $point->id,
                'visitor_count' => $data['visitor_count'],
                'fee' => $data['fee'],
                'facility_count' => $data['facility_count']
            ]);
        });
    }

    public function delete(Site $site)
    {
        DB::transaction(function() use($site) {
            Path::where('point_a_id', $site->point->id)
                ->orWhere('point_b_id', $site->point->id)
                ->delete();

            $site->delete();
            $site->point->delete();
        });

        return back()
            ->with('message.success', __('messages.delete.success'));
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

    public function analysis()
    {
        $points = Point::query()
            ->isSite()
            ->select('id', 'name')
            ->with('site:point_id,fee,visitor_count,facility_count')
            ->get()
            ->keyBy('id');

        $aspects = [
            'fee' => 'Harga',
            'visitor_count' => 'J. Pengunjung',
            'facility_count' => 'J. Fasilitas'
        ];

        // Calculate local comparisons
        $local_comparisons = collect();
        $local_comparison_sums = collect();

        foreach ($aspects as $aspect_key => $aspect_name) {
            
            $data = collect();
            $sums = collect();
            
            foreach ($points as $point) {
                $comparisons = collect();

                foreach ($points as $another_point) {
                    $comparison = $point->site->$aspect_key / $another_point->site->$aspect_key;
                    $comparisons->put($another_point->id, $comparison);
                    $sums->put($another_point->id, $comparison + ($sums[$another_point->id] ?? 0));
                }

                $data->put($point->id, $comparisons);
            }

            $local_comparisons->put($aspect_key, $data);
            $local_comparison_sums->put($aspect_key, $sums);
        }

        // Calculate local priorities
        $local_priorities = collect();
        
        foreach ($local_comparisons as $aspect_key => $comparison_list) {
            $data = collect();

            foreach ($comparison_list as $point_id => $comparisons) {
                $comparison_data = collect();

                foreach ($comparisons as $c_point_id => $comparison) {
                    $comparison_data->put($c_point_id, $comparison / $local_comparison_sums[$aspect_key][$c_point_id]);
                }

                $data->put($point_id, $comparison_data);
            }

            $local_priorities->put($aspect_key, $data);
        }

        return view('site.analysis', compact('points', 'aspects', 'local_comparisons', 'local_comparison_sums', 'local_priorities'));
    }
}
