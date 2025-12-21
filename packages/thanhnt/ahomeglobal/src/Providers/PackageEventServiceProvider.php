<?php

namespace Thanhnt\Ahomeglobal\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Thanhnt\Ahomeglobal\Events\CartSaveEvent;
use Thanhnt\Ahomeglobal\Events\HomeSaveEvent;

final class PackageEventServiceProvider extends EventServiceProvider
{
	/**
	 * The event listener mappings for the package.
	 *
	 * @var array
	 */
	protected $listen = [
		HomeSaveEvent::class => [
			\Thanhnt\Ahomeglobal\Listeners\HomeSaveListener::class,
		],
		CartSaveEvent::class => [
			\Thanhnt\Ahomeglobal\Listeners\CartSaveListener::class
		]
	];

	/**
	 * Register any package authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		parent::boot();
	}
}
