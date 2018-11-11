<?php

use Illuminate\Database\Seeder;
use App\SiteCategory;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $site_category_ids = SiteCategory::select('id')->pluck('id');

        foreach ($site_category_ids as $site_category_id) {
            factory(App\Site::class, 6)->create(['site_category_id' => $site_category_id])->each(function ($site) {
                $site->addMedia(__DIR__ . '/lemukutan.png')->preservingOriginal()->toMediaCollection(config('media.collections.images'));
            });
        }
    }
}
