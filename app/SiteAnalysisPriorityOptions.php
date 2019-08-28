<?php

namespace App;

use App\Interfaces\SiteAnalysisPriorityOptions as SiteAnalysisPriorityOptionsInterface;

class SiteAnalysisPriorityOptions implements SiteAnalysisPriorityOptionsInterface
{
    public function visitor_count()
    {
        return [
            "Sedikit" => 1,
            "Sedang" => 5,
            "Banyak" => 10,
        ];
    }

    public function fee()
    {
        return [
            "Rendah" => 1,
            "Sedang" => 5,
            "Tinggi" => 10,
        ];
    }

    public function facility_count()
    {
        return [
            "Sedikit" => 1,
            "Sedang" => 5,
            "Banyak" => 10,
        ];
    }
}
