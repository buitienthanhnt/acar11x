<?php

namespace Thanhnt\Ahomeglobal;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class AhomeglobalProvider extends ServiceProvider
{
	/**
	 * 
	 */
	public function register(): void
	{
		/**
		 * merge package define config to global.
		 */
		$this->mergeConfigFrom(__DIR__ . '/config/config.php', 'ahomeglobal');
	}

	/**
	 * 
	 */
	public function boot(): void
	{
		// Load views, routes, migrations, publish assets, etc.
		$this->loadViewsFrom(__DIR__ . '/resources/views', 'ahomelobal');
		/**
		 * load router file register
		 * need define web middleware for router unless the request missing session data. 
		 */
		Route::middleware([
			\Illuminate\Session\Middleware\StartSession::class,
			\App\Http\Middleware\HandleInertiaRequests::class,
		])->group(function () {
			$this->loadRoutesFrom(__DIR__ . '/routes/adminhtml.php');
			$this->loadRoutesFrom(__DIR__ . '/routes/front.php');
		});

		/**
		 * load migration define 
		 * make migration: php artisan make:migration create_homes_table --path=packages/thanhnt/ahomeglobal/src/database/migrations
		 */
		$this->loadMigrationsFrom(__DIR__ . '/database/migrations');
		/**
		 * load factory for package
		 */
		$this->loadFactoriesFrom(__DIR__ . '/database/factories');

		/**
		 * coppy config file from the package to global config
		 * php artisan vendor:publish --provider="Thanhnt\Ahomeglobal\AhomeglobalProvider"
		 */
		$this->publishes([
			__DIR__ . '/config/config.php' => config_path('ahomeglobal.php'),
		], 'ahomeglobal-config');

		/**
		 * publish inertiaJs component to js/Pages views and active running with controllers Inertial::render()
		 * php artisan vendor:publish --tag=ahomeglobal-inertiajs
		 */
		$this->publishes([
			__DIR__ . '/resources/js' => resource_path('js/Pages'),
		], 'ahomeglobal-inertiajs');

		/**
		 * publish database seeders to seeders global
		 * php artisan vendor:publish --tag=ahomeglobal-seeders
		 */
		$this->publishes([
            __DIR__.'/database/seeders' => database_path('seeders'),
        ], 'ahomeglobal-seeders');
	}
}
