<?php

namespace Thanhnt\Ahomeglobal\Database\Seeders;

use Illuminate\Database\Seeder;
use Thanhnt\Ahomeglobal\Models\ExpectOrder;

final class ExpectOrderSeeder extends Seeder
{
	/**
	 * run: php artisan db:seed --class=Thanhnt\\Ahomeglobal\\Database\\Seeders\\ExpectOrderSeeder
	 */
	public function run(): void
	{
		/**
		 * call to createHome protected function
		 */
		$this->createOrder();
	}

	/**
	 * create new home row by factory
	 */
	protected function createOrder()
	{
		ExpectOrder::factory()->create();
	}
}
