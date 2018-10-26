<?php

use Illuminate\Database\Seeder;

class PointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Point::class, 25)->create(['type' => 'WAYPOINT']);
        factory(App\Point::class, 5)->create(['type' => 'SITE']);
    }
}
