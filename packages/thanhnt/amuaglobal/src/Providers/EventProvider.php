<?php

namespace Thanhnt\Amuaglobal\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Thanhnt\Amuaglobal\Events\OrderSave;

final class EventProvider extends EventServiceProvider
{
	/**
	 * The event listener mappings for the package.
	 *
	 * @var array
	 */
	protected $listen = [
		OrderSave::class => [
			\Thanhnt\Amuaglobal\Listeners\OrderSaveListen::class,
		],
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
