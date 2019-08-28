<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\SiteAnalysisPriorityOptions as SiteAnalysisPriorityOptionsInterface;
use App\SiteAnalysisPriorityOptions;

class SiteAnalysisPriorityOptionsProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SiteAnalysisPriorityOptionsInterface::class, SiteAnalysisPriorityOptions::class);
    }
}
