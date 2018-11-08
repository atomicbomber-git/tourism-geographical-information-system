<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteCategory extends Model
{
    public $fillable = [ 'name' ];

    public function sites()
    {
        return $this->hasMany(Site::class, 'site_category_id');
    }
}
