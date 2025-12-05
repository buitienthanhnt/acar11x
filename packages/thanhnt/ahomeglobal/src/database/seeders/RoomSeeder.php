<?php

namespace Thanhnt\Ahomeglobal\Database\Seeders;

use Illuminate\Database\Seeder;
use Thanhnt\Ahomeglobal\Models\Room;

final class RoomSeeder extends Seeder
{
	/**
	 * php artisan db:seed --class=Thanhnt\\Ahomeglobal\\Database\\Seeders\\HomeSeeder
	 */
	public function run(): void
	{
		/**
		 * call to createHome protected function
		 */
		$this->createRoom();
	}

	/**
	 * create new home row by factory
	 */
	protected function createRoom()
	{
		Room::factory()->create();
	}
}
