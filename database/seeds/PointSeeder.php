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
        factory(App\Point::class, 10)->create(['type' => 'WAYPOINT']);
        factory(App\Point::class, 10)->create(['type' => 'SITE']);
    }
}
