<?php

use Illuminate\Database\Seeder;
use App\Slide;

class SlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $slide = Slide::create(['name' => 'Slide 01', 'description' => 'Lorem ipsum dolor sit amet']);
        $slide->addMedia(__DIR__ . '/yosemite_01.jpg')->preservingOriginal()->toMediaCollection(config('media.collections.images'));
        $slide = Slide::create(['name' => 'Slide 02', 'description' => 'Lorem ipsum dolor sit amet']);
        $slide->addMedia(__DIR__ . '/yosemite_02.jpg')->preservingOriginal()->toMediaCollection(config('media.collections.images'));
        $slide = Slide::create(['name' => 'Slide 03', 'description' => 'Lorem ipsum dolor sit amet']);
        $slide->addMedia(__DIR__ . '/yosemite_03.jpg')->preservingOriginal()->toMediaCollection(config('media.collections.images'));
    }
}
