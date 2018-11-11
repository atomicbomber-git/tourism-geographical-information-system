<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SiteCategory;

class SiteCategoryItemController extends Controller
{
    public function index(SiteCategory $site_category)
    {
        $site_category->load([
            'sites:id,visitor_count,fee,facility_count,description,site_category_id,point_id',
            'sites.point:id,name'
        ]);

        return view('site-category-item.index', compact('site_category'));
    }
}
