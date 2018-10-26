<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Path extends Model
{
    public $fillable = [
        'point_a_id', 'point_b_id'
    ];

    public function point_a()
    {
        return $this->belongsTo(Point::class, 'point_a_id');
    }

    public function point_b()
    {
        return $this->belongsTo(Point::class, 'point_b_id');
    }
}
