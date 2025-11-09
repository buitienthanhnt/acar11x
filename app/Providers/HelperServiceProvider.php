<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * service provider support for define custom global function same as helper function.
 */
class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        require_once app_path('Helper/GlobalFunction.php');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
