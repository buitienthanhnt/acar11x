<?php

namespace Thanhnt\Ahomeglobal\Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Thanhnt\Ahomeglobal\Models\Attr;
use Thanhnt\Ahomeglobal\Models\Gallery;
use Thanhnt\Ahomeglobal\Models\Home;
use Thanhnt\Ahomeglobal\Models\Room;
use Thanhnt\Ahomeglobal\Models\Types\AttrInterface;
use Thanhnt\Ahomeglobal\Models\Types\GalleryInterface;
use Thanhnt\Ahomeglobal\Models\Types\HomeInterface;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

final class HomeSeeder extends Seeder
{
	/**
	 * run: php artisan db:seed --class=Thanhnt\\Ahomeglobal\\Database\\Seeders\\HomeSeeder
	 */
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
		Home::factory()->count(50)->state(new Sequence(
			// [Home::DISTRICT => 'Quận 1 TP HCM',],
			// [Home::DISTRICT => 'Quận 2 TP HCM',],
			[Home::DISTRICT => 'Quận 3 TP HCM',],
			[Home::DISTRICT => 'Sapa, Lào Cai',],
			[Home::DISTRICT => 'Quản Bạ, Hà Giang',],
			[Home::DISTRICT => 'Yên Minh, Hà Giang',],
			[Home::DISTRICT => 'Đống Đa, Hà Nội',],
			[Home::DISTRICT => 'Thanh Xuân, Hà Nội',],
			[Home::DISTRICT => 'Tây Hồ, Hà Nội',],
			[Home::DISTRICT => 'Hoàn Kiếm, Hà Nội',],
		))->state(new Sequence(
			[Home::DESCRIPTION => 'Hướng nhìn thông thoáng,'],
			[Home::DESCRIPTION => 'Đầy đủ tiện nghi, hiện đại'],
			[Home::DESCRIPTION => 'Gần trung tâm du lịch, thuận tiện di chuyển'],
			[Home::DESCRIPTION => 'Dịch vụ nghỉ dưỡng, giải trí hiện đại'],
			[Home::DESCRIPTION => 'Giá cả ưu đãi, tốt nhất khu vực'],
			[Home::DESCRIPTION => 'hỗ trợ xe di chuyển miễn phí tới các địa điểm du lịch'],
		))->state(new Sequence(
			[HomeInterface::IMAGE_PATH => 'http://acar11x.dev/storage/photos/tong-hop/AAHcEqk.jpeg'],
			[HomeInterface::IMAGE_PATH => 'http://acar11x.dev/storage/photos/demo/AA1NEevD.jpeg'],
			[HomeInterface::IMAGE_PATH => 'http://acar11x.dev/storage/photos/demo/AA1NEZAR.jpeg'],
			[HomeInterface::IMAGE_PATH => 'http://acar11x.dev/storage/photos/tong-hop/AAHcQjm.jpeg'],
			[HomeInterface::IMAGE_PATH => 'http://acar11x.dev/storage/photos/tong-hop/AAHghMa.jpeg'],
			[HomeInterface::IMAGE_PATH => 'http://acar11x.dev/storage/photos/tong-hop/AA1Ochyv.jpeg'],
			[HomeInterface::IMAGE_PATH => 'http://acar11x.dev/storage/photos/shares/uploads/khukinhtevanphong-1710833234-5864-1710833309.jpg'],
		))
			->has(
				Room::factory()->count(6)->state(new Sequence(
					[RoomInterface::TITLE => 'P-101',],
					[RoomInterface::TITLE => 'P-102',],
					[RoomInterface::TITLE => 'P-103',],
					[RoomInterface::TITLE => 'P-104',],
					[RoomInterface::TITLE => 'P-105',],
					[RoomInterface::TITLE => 'P-106',],
				))->state(new Sequence(
					[HomeInterface::IMAGE_PATH => 'http://acar11x.dev/storage/photos/shares/uploads/62161275_1112810635573522_8741650106958217216_n_104932_084827.jpg'],
					[HomeInterface::IMAGE_PATH => 'http://acar11x.dev/storage/photos/shares/uploads/10301190_679242935474644_4276477968542896581_n_084425.jpg'],
					[HomeInterface::IMAGE_PATH => 'http://acar11x.dev/storage/photos/shares/uploads/24883488_777324299122159_6358556149619703533_o_084431.jpg'],
					[HomeInterface::IMAGE_PATH => 'http://acar11x.dev/storage/photos/shares/uploads/15439947_618736711647586_7282073870375019745_n_084429.jpg'],
				))->state(new Sequence(
					[RoomInterface::DESCRIPTION => 'Hướng nhìn thông thoáng,'],
					[RoomInterface::DESCRIPTION => 'Đầy đủ tiện nghi, hiện đại'],
					[RoomInterface::DESCRIPTION => 'Nóng lạnh, điều hòa'],
					[RoomInterface::DESCRIPTION => 'Tủ bếp, phù hợp gia đình'],
					[RoomInterface::DESCRIPTION => 'Tủ lạnh, máy giặt'],
					[RoomInterface::DESCRIPTION => 'Ban công, phòng làm việc riêng'],
				))->state(new Sequence(
					[RoomInterface::TYPE => RoomInterface::TYPE_VALUE[0]['value']],
					[RoomInterface::TYPE => RoomInterface::TYPE_VALUE[1]['value']],
					[RoomInterface::TYPE => RoomInterface::TYPE_VALUE[2]['value']],
					[RoomInterface::TYPE => RoomInterface::TYPE_VALUE[3]['value']],
					[RoomInterface::TYPE => RoomInterface::TYPE_VALUE[4]['value']],
				))->has(
					Attr::factory()->count(3)->state(new Sequence(
						[AttrInterface::TYPE => 'room', AttrInterface::KEY => 'price', AttrInterface::VALUE => '500',],
						[AttrInterface::TYPE => 'room', AttrInterface::KEY => 'rate', AttrInterface::VALUE => '3',],
						[AttrInterface::TYPE => 'room', AttrInterface::KEY => 'persion', AttrInterface::VALUE => '1',],
					), HomeInterface::ATTR)
				),
				HomeInterface::ROOMS
			)->has(
				Attr::factory()->count(3)->state(new Sequence(
					[AttrInterface::TYPE => 'home', AttrInterface::KEY => 'rate', AttrInterface::VALUE => '3',],
					[AttrInterface::TYPE => 'home', AttrInterface::KEY => 'g_map', AttrInterface::VALUE => '21.024272874937907, 105.83923959858203',],
					[AttrInterface::TYPE => 'home', AttrInterface::KEY => 'location', AttrInterface::VALUE => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7428.228619517692!2d105.5720075010085!3d21.424751495375933!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3134e8bb8e664383%3A0x929151432507557!2zQ2jhu6MgVGFtIFF1YW4!5e0!3m2!1svi!2s!4v1765458496027!5m2!1svi!2s',]
				), HomeInterface::ATTR)
			)->has(
				Gallery::factory()->count(3)->state(new Sequence(
					[GalleryInterface::TYPE => 'home', GalleryInterface::PATH => '/storage/photos/demo/AA1NEZAR.jpeg',],
					[GalleryInterface::TYPE => 'home', GalleryInterface::PATH => '/storage/photos/demo/AA1NEUFg.jpeg',],
					[GalleryInterface::TYPE => 'home', GalleryInterface::PATH => '/storage/photos/tong-hop/AA1gxkko.jpeg',]
				)),
				HomeInterface::GALLERY
			)
			->create();
	}
}
