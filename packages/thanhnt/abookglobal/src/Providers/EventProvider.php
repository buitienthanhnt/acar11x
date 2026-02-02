<?php

namespace Thanhnt\Abookglobal\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Thanhnt\Abookglobal\Events\BookOrderSave;

final class EventProvider extends EventServiceProvider
{
	/**
	 * The event listener mappings for the package.
	 *
	 * @var array
	 */
	protected $listen = [
		BookOrderSave::class => [
			\Thanhnt\Abookglobal\Listeners\BookOrderSaveListen::class,
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
