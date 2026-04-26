<?php

namespace Thanhnt\Acarglobal;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class AcarglobalProvider extends ServiceProvider
{
	/**
	 * 
	 */
	public function register(): void {}

	/**
	 * 
	 */
	public function boot(): void
	{
		/**
		 * load route file
		 */
		Route::middleware([
			...Route::getMiddlewareGroups()['web'],
			\Thanhnt\Amuaglobal\Middleware\PreventBackHistory::class,
		])->group(function () {
			// Load views, routes, migrations, publish assets, etc.
			$this->loadRoutesFrom(__DIR__ . '/routes/front.php');
		});
		/**
		 * load migration folder
		 * make migration: php artisan make:migration create_cars_table --path=packages/thanhnt/acarglobal/src/database/migrations
		 * make model: 	   php artisan acar:make-model Acar
		 * php artisan make:migration create_activities_table --path=packages/thanhnt/acarglobal/src/database/migrations
		 */
		$this->loadMigrationsFrom(__DIR__ . '/database/migrations');
	}
}
