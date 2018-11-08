<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\SiteCategory;

class SiteCategoryController extends Controller
{
    public function index()
    {
        $site_categories = SiteCategory::select('name', 'id')
            ->withCount('sites')
            ->get();

        return view('site-category.index', compact('site_categories'));
    }

    public function create()
    {
        return view('site-category.create');
    }

    public function store()
    {
        $data = $this->validate(request(), [
            'name' => 'required|string|unique|max:50'
        ]);

        SiteCategory::create($data);

        return redirect()
            ->route('site-category.index')
            ->with('message.success', __('messages.create.success'));
    }

    public function edit(SiteCategory $site_category)
    {
        return view('site-category.edit', compact('site_category'));
    }

    public function update(SiteCategory $site_category)
    {
        $data = $this->validate(request(), [
            'name' => ['required', 'string', Rule::unique('site_categories')->ignore($site_category->id), 'max:50']
        ]);

        $site_category->update($data);

        return back()
            ->with('message.success', __('messages.update.success'));
    }

    public function delete(SiteCategory $site_category)
    {
        if ($site_category->sites()->count()) {
            abort(422);
        }

        $site_category->delete();

        return back()
            ->with('message.success', __('messages.delete.success'));
    }
}
