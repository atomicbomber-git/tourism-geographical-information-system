<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Point;

class AnalysisController extends Controller
{
    public function analysis()
    {
        $data = $this->validate(request(), [
            'fee' => 'required|numeric',
            'visitor_count' => 'required|numeric',
            'facility_count' => 'required|numeric',
        ]);

        $aspects = [
            'fee' => 'Harga',
            'visitor_count' => 'J. Pengunjung',
            'facility_count' => 'J. Fasilitas'
        ];

        $aspect_comparisons = collect();
        $aspect_comparison_sums = collect();

        foreach ($data as $key_h => $value_h) {
            $aspect_comparisons[$key_h] = collect();
            foreach ($data as $key_r => $value_r) {
                $ratio = $value_h / $value_r;
                $aspect_comparisons[$key_h][$key_r] = $ratio;
                $aspect_comparison_sums[$key_r] = $aspect_comparison_sums[$key_r] ?? 0 + $ratio;
            }
        }

        $aspect_priorities = $aspect_comparisons
            ->map(function($aspect_comparison) use($aspect_comparison_sums) {
                return $aspect_comparison->map(function($value, $key) use($aspect_comparison_sums) {
                    return $value / $aspect_comparison_sums[$key];
                });
            });

        $points = Point::query()
            ->isSite()
            ->select('id', 'name')
            ->with('site:point_id,fee,visitor_count,facility_count')
            ->get()
            ->keyBy('id');

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

        return view(
            'site.analysis',
            compact('points', 'aspects', 'local_comparisons', 'local_comparison_sums', 'local_priorities', 'aspect_comparisons', 'aspect_comparison_sums')
        );
    }

    public function analyze()
    {
        $aspects = [
            'fee' => 'Harga',
            'visitor_count' => 'J. Pengunjung',
            'facility_count' => 'J. Fasilitas'
        ];

        return view('site.analyze', compact('aspects'));
    }
}
