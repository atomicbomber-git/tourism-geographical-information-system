<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminSeeder::class);
        $this->call(PointSeeder::class);
        $this->call(SiteCategorySeeder::class);
        $this->call(SiteSeeder::class);
        $this->call(PathSeeder::class);
        $this->call(SlideSeeder::class);
    }
}
