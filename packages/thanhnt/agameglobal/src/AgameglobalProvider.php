<?php

namespace Thanhnt\Agameglobal;

use Illuminate\Support\ServiceProvider;

final class AgameglobalProvider extends ServiceProvider
{

	function register()
	{
		// Register bindings, singletons, etc.
		$this->mergeConfigFrom(__DIR__ . '/Config/config.php', 'agameglobal');
	}

	public function boot()
	{
		// Load views, routes, migrations, publish assets, etc.
		$this->loadViewsFrom(__DIR__ . '/resources/views', 'agameglobal');

		$this->loadRoutesFrom(__DIR__ . '/routes/web.php');

		/**
		 * publish inertiaJs component to js/Pages views and active running with controllers Inertial::render()
		 * php artisan vendor:publish --tag=agameglobal-inertiajs
		 */
		$this->publishes([
			__DIR__ . '/resources/js' => resource_path('js/Pages'),
		], 'agameglobal-inertiajs');
	}
}
