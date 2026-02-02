<?php

namespace Thanhnt\Abookglobal;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Thanhnt\Abookglobal\Providers\EventProvider;

final class AbookglobalProvider extends ServiceProvider
{
	/**
	 * 
	 */
	public function register(): void
	{
		/**
		 * merge package define config to global.
		 */
		$this->mergeConfigFrom(__DIR__ . '/config/config.php', 'abookglobal');

		/**
		 * load for event service provider.
		 */
		$this->app->register(EventProvider::class);
	}

	/**
	 * 
	 */
	public function boot(): void
	{
		/**
		 * load router file register
		 * need define web middleware for router unless the request missing session data. 
		 */
		Route::middleware([
			...Route::getMiddlewareGroups()['web'],
			\Thanhnt\Ahomeglobal\Middleware\MergeInertiaConfig::class,
		])->group(function () {
			$this->loadRoutesFrom(__DIR__ . '/routes/adminhtml.php');
			$this->loadRoutesFrom(__DIR__ . '/routes/front.php');
		});

		// Load views, routes, migrations, publish assets, etc.
		$this->loadViewsFrom(__DIR__ . '/resources/views', 'abookglobal');

		/**
		 * load migration define 
		 * make migration: php artisan make:migration create_books_table --path=packages/thanhnt/abookglobal/src/Database/Migrations
		 * rollback: php artisan migrate:rollback --step=1
		 */
		$this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
		/**
		 * load factory for package
		 */
		$this->loadFactoriesFrom(__DIR__ . '/Database/Factories');

		/**
		 * publish inertiaJs component to js/Pages views and active running with controllers Inertial::render()
		 * php artisan vendor:publish --tag=abookglobal-inertiajs
		 */
		$this->publishes([
			__DIR__ . '/resources/js' => resource_path('js/Pages'),
		], 'abookglobal-inertiajs');
	}
}
