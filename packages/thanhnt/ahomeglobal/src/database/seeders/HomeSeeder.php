<?php

namespace Thanhnt\Ahomeglobal\Database\Seeders;

use Illuminate\Database\Seeder;
use Thanhnt\Ahomeglobal\Models\Home;

final class HomeSeeder extends Seeder
{
	public function run(): void
	{
		/**
		 * call to createHome protected function
		 */
		$this->createHome();
	}

	/**
	 * create new home row by factory
	 */
	protected function createHome()
	{
		Home::factory()->create();
	}
}
