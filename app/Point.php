<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    const TYPES = [
        'WAYPOINT' => 'Waypoint',
        'SITE' => 'Situs'
    ];

    public $fillable = [
        'name', 'latitude', 'longitude', 'waypoint', 'type'
    ];

    public function paths_from()
    {
        return $this->hasMany(Path::class, 'point_a_id');
    }

    public function paths_to()
    {
        return $this->hasMany(Path::class, 'point_b_id');
    }
}
