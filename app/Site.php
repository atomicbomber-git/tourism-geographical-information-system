<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    public $fillable = [
        'point_id', 'visitor_count', 'fee', 'facility_count'
    ];

    public function point()
    {
        return $this->belongsTo(Point::class);
    }
}
