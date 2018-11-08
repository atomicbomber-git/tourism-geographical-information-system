<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Site extends Model implements HasMedia
{
    use HasMediaTrait;

    public $fillable = [
        'point_id', 'visitor_count', 'fee', 'facility_count', 'site_category_id', 'description'
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
