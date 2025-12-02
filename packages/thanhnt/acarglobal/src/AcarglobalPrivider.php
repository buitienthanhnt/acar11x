<?php

namespace Thanhnt\Acarglobal;

use Illuminate\Support\ServiceProvider;

final class AcarglobalPrivider extends ServiceProvider
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
		$this->loadRoutesFrom(__DIR__ . '/routes/front.php');
	}
}
