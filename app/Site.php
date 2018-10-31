<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    public $fillable = [
        'point_id', 'visitor_count', 'fee', 'facility_count', 'site_category_id'
    ];

    public function point()
    {
        return $this->belongsTo(Point::class);
    }

    public function category()
    {
        return $this->belongsTo(SiteCategory::class, 'site_category_id');
    }
}
