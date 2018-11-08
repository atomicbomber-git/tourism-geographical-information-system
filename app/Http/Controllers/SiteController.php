<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Point;
use App\Path;
use App\Site;
use App\SiteCategory;

class SiteController extends Controller
{
    public function index() {

        $points = Point::query()
            ->isSite()
            ->select('id', 'name', 'type')
            ->with('site:id,point_id,visitor_count,fee,facility_count,site_category_id')
            ->with('site.category:id,name')
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
        
        $categories = SiteCategory::select('id', 'name')
            ->get();

        return view('site.create', compact('points', 'categories'));
    }

    public function edit(Site $site)
    {
        $points = Point::query()
            ->select('id', 'name', 'latitude', 'longitude', 'type')
            ->with('paths_from:point_a_id,point_b_id')
            ->get()
            ->keyBy('id');
        
        $categories = SiteCategory::select('id', 'name')
            ->get();

        $site->load('point');
        return view('site.edit', compact('site', 'points', 'categories'));
    }

    public function update(Site $site)
    {
        $data = $this->validate(request(), [
            'name' => ['required', 'string', Rule::unique('points')->ignore($site->point->id)],
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'visitor_count' => 'required|numeric',
            'fee' => 'required|numeric',
            'facility_count' => 'required|numeric',
            'site_category_id' => 'required|exists:site_categories,id',
            'description' => 'required|string|max:2000',
            'image' => 'nullable|file|mimes:jpg,jpeg,svg,png'
        ]);

        DB::transaction(function() use($data, $site) {
            $site->point->update([
                'name' => $data['name'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'type' => 'SITE'
            ]);

            $site->update([
                'visitor_count' => $data['visitor_count'],
                'fee' => $data['fee'],
                'facility_count' => $data['facility_count'],
                'site_category_id' => $data['site_category_id'],
                'description' => $data['description'],
            ]);

            if (!empty($data['image'])) {
                $site->clearMediaCollection(config('media.collections.images'));
                $site->addMediaFromRequest('image')
                    ->toMediaCollection(config('media.collections.images'));
            }
        });
    }

    public function store()
    {
        $data = $this->validate(request(), [
            'name' => 'required|string|unique:points',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'visitor_count' => 'required|numeric',
            'fee' => 'required|numeric',
            'facility_count' => 'required|numeric',
            'site_category_id' => 'required|exists:site_categories,id',
            'description' => 'required|string|max:2000',
            'image' => 'required|file|mimes:jpg,jpeg,svg,png'
        ]);

        DB::transaction(function() use($data) {
            $point = Point::create([
                'name' => $data['name'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'type' => 'SITE'
            ]);

            $site = Site::create([
                'point_id' => $point->id,
                'visitor_count' => $data['visitor_count'],
                'fee' => $data['fee'],
                'facility_count' => $data['facility_count'],
                'site_category_id' => $data['site_category_id'],
                'description' => $data['description'],
            ]);

            $site->addMediaFromRequest('image')
                ->toMediaCollection(config('media.collections.images'));
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

    public function image(Site $site)
    {
        $image = $site->getFirstMedia(config('media.collections.images'));
        if (empty($image)) { abort(404); }
        return response()->file($image->getPath());
    }

    public function thumbnail(Site $site)
    {
        $image = $site->getFirstMedia(config('media.collections.images'));
        if (empty($image)) { abort(404); }
        return response()->file($image->getPath(config('media.collections.thumbnails')));
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
