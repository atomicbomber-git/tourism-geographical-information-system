<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Slide;

class SlideController extends Controller
{
    public function index()
    {
        $slides = Slide::select('id', 'name', 'description')->get();
        return view('slide.index', compact('slides'));
    }

    public function create()
    {
        return view('slide.create');
    }

    public function delete(Slide $slide)
    {
        DB::transaction(function() use($slide) {
            $slide->delete();
        });

        return back()
            ->with('message.success', __('messages.delete.success'));
    }

    public function store()
    {
        $data = $this->validate(request(), [
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:500',
            'image' => 'required|mimes:jpg,jpeg,svg,png'
        ]);

        DB::transaction(function () use ($data) {
            $slide = Slide::create($data);
            $slide->addMediaFromRequest('image')
                ->toMediaCollection(config('media.collections.images'));
        });

        return redirect()
            ->route('slide.index')
            ->with('message.success', __('messages.create.success'));
    }

    public function edit(Slide $slide)
    {
        return view('slide.edit', compact('slide'));
    }

    public function update(Slide $slide)
    {
        $data = $this->validate(request(), [
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:500',
            'image' => 'sometimes|nullable|mimes:jpg,jpeg,svg,png'
        ]);

        DB::transaction(function () use ($slide, $data) {
            $slide->update($data);

            if (!empty($data['image'])) {
                $slide->clearMediaCollection(config('media.collections.images'));
                $slide->addMediaFromRequest('image')
                    ->toMediaCollection(config('media.collections.images'));
            }
        });

        return back()
            ->with('message.success', __('messages.update.success'));
    }

    public function image(Slide $slide)
    {
        $slide_image = $slide->getFirstMedia(config('media.collections.images'));

        if (empty($slide_image)) {
            abort(404);
        }

        return response()
            ->file($slide_image->getPath());
    }
}
