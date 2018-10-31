<?php

use Illuminate\Database\Seeder;
use App\SiteCategory;

class SiteCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SiteCategory::create(['name' => 'Alam']);
        SiteCategory::create(['name' => 'Museum']);
        SiteCategory::create(['name' => 'Pantai']);
        SiteCategory::create(['name' => 'Sejarah']);
    }
}
