<?php

use App\Path;
use App\Point;
use App\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemovePointForDijkstraTestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function() {
            $point_name = "Sekip Lama";

            Path::query()
                ->where(function ($query) use($point_name) {
                    $query
                        ->orWhereHas("point_from", function ($query) use($point_name) {
                            $query->where("name", "not like", "%{$point_name}%");
                        })
                        ->orWhereHas("point_to", function ($query) use($point_name) {
                            $query->where("name", "not like", "%{$point_name}%");
                        });
                })
                ->delete();

            Site::query()
                ->whereHas("point", function ($query) use($point_name) {
                    $query->where("name", "not like", "%{$point_name}%");
                })
                ->delete();

            Point::query()
                ->where("name", "not like", "%{$point_name}%")
                ->delete();
        });
    }
}
