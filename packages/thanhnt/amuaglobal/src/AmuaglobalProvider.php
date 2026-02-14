<?php

namespace Thanhnt\Amuaglobal;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Thanhnt\Amuaglobal\Providers\EventProvider;
use Thanhnt\Amuaglobal\Providers\PaymentServiceProvider;

class AmuaglobalProvider extends ServiceProvider
{
	public function register()
	{
		// Register bindings, singletons, etc.
		$this->mergeConfigFrom(__DIR__ . '/Config/config.php', 'amuaglobal');

		/**
		 * load for event service provider.
		 */
		$this->app->register(EventProvider::class);
		/**
		 * load for payment service provider
		 */
		$this->app->register(PaymentServiceProvider::class);
	}

	public function boot()
	{
		/**
		 * load router file register
		 * need define web middleware for router unless the request missing session data. 
		 */
		Route::middleware([
			...Route::getMiddlewareGroups()['web'],
			\Thanhnt\Amuaglobal\Middleware\MergeInertiaConfig::class,
		])->group(function () {
			// Load views, routes, migrations, publish assets, etc.
			$this->loadRoutesFrom(__DIR__ . '/routes/front.php');
			$this->loadRoutesFrom(__DIR__ . '/routes/adminhtml.php');
		});

		// Load views, routes, migrations, publish assets, etc.
		$this->loadViewsFrom(__DIR__ . '/resources/views', 'amuaglobal');

		/**
		 * load migration define 
		 * make migration: php artisan make:migration create_products_table --path=packages/thanhnt/amuaglobal/src/Database/Migrations
		 * rollback: php artisan migrate:rollback --step=1
		 */
		$this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
		/**
		 * load factory for package
		 */
		$this->loadFactoriesFrom(__DIR__ . '/Database/Factories');
		/**
		 * khai báo các command
		 * dùng hàm: is_subclass_of; để kiểm tra các class định nghĩa trong đây: https://www.php.net/manual/en/function.is-subclass-of.php
		 */
		$this->commands([
			\Thanhnt\Amuaglobal\Commands\DemoCommand::class,
			\Thanhnt\Amuaglobal\Commands\MakePackageModelCommand::class,
		]);

		/**
		 * publish inertiaJs component to js/Pages views and active running with controllers Inertial::render()
		 * php artisan vendor:publish --tag=amuaglobal-inertiajs
		 */
		$this->publishes([
			__DIR__ . '/resources/js' => resource_path('js/Pages'),
		], 'amuaglobal-inertiajs');

		/**
		 * coppy config file from the package to global config
		 * php artisan vendor:publish --provider="Thanhnt\Amuaglobal\AmuaglobalProvider"
		 */
		$this->publishes([
			__DIR__ . '/config/config.php' => config_path('amuaglobal.php'),
		], 'amuaglobal-config');

		/**
		 * publish assets to public folder
		 * php artisan vendor:publish --tag=amuaglobal-assets
		 */
		$this->publishes([
			__DIR__ . '/resources/public' => public_path('amua'),
		], 'amuaglobal-assets');
	}
}
