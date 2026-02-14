<?php

namespace Thanhnt\Ahomeglobal\Repository;

use Exception;
use Thanhnt\Ahomeglobal\Events\HomeSaveEvent;
use Thanhnt\Amuaglobal\Helper\ModelHelper;
use Thanhnt\Amuaglobal\Models\Attr;
use Thanhnt\Ahomeglobal\Models\Home;
use Thanhnt\Amuaglobal\Models\Types\AttrInterface;
use Thanhnt\Amuaglobal\Models\Types\GalleryInterface;
use Thanhnt\Ahomeglobal\Models\Types\HomeInterface;

final class HomeRepository
{
	public function __construct(
		protected Home $home,
		protected Attr $attr,
		protected ModelHelper $modelHelper,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * create new home model
	 * @return Home
	 */
	public function createHome(array $data)
	{
		$newHome = $this->home->factory()->create($this->modelHelper->massDataAttribute(HomeInterface::FILLED_FILEDS, $data));
		if ($newHome) {
			\Illuminate\Support\Facades\Event::dispatch(new HomeSaveEvent($newHome));
			$this->saveHomeAttr($newHome, $data['attrs'] ?? null);
		}
		$this->saveGallery($newHome, explode(',', $data[HomeInterface::GALLERY]) ?? []);
		return $newHome;
	}

	/**
	 * @param Home $home
	 * @param string[] $data
	 * @return void
	 */
	public function saveGallery(Home $home, array $data)
	{
		/**
		 * delete old gallery
		 */
		$home->gallery()->delete();
		/**
		 * create new gallery
		 */
		$home->gallery()->createMany(
			array_map(function ($item) {
				return [
					GalleryInterface::TYPE => 'home',
					GalleryInterface::PATH => $item,
				];
			}, $data)
		);
	}

	/**
	 * insert multil record home attribute
	 * @param Home $home
	 * @param array $data
	 * @return bool
	 */
	protected function saveHomeAttr(Home $home, array|null $data)
	{
		$this->deleteHomeAttrs($home);

		$listAttr = [];
		/**
		 * format request home attribute
		 */
		foreach ($data as $key => $value) {
			$listAttr[] = [
				AttrInterface::SOURCE_ID => $home->id,
				AttrInterface::TYPE => 'home',
				AttrInterface::KEY => $key,
				AttrInterface::VALUE => $value,
			];
		}

		/**
		 * insert for multi record
		 * @return bool
		 */
		return $newAttr = Attr::insert($listAttr);
	}

	/**
	 * 
	 */
	public function updateHome($homeId, $data)
	{
		/**
		 * @var Home|null $home
		 */
		$home = $this->home->find($homeId);
		if (!$home) {
			return;
		}
		$home->fill($data);
		$home->save();
		/**
		 * save custom attributes
		 */
		$this->saveHomeAttr($home, $data['attrs'] ?? []);
		/**
		 * save for gallery
		 */
		$this->saveGallery($home, explode(',', $data[HomeInterface::GALLERY]) ?? []);
	}

	/**
	 * @param Home $home
	 * @return void
	 */
	public function deleteHomeAttrs($home)
	{
		/**
		 * delete old attributes
		 */
		$home->attr()->forceDelete();
	}

	/**
	 * @param Home $home
	 * @return void
	 */
	public function deleteRooms($home)
	{
		$home->room()->delete();
	}

	public function deleteHome(int $homeId)
	{
		$home = $this->home->find($homeId);
		if (!$home) {
			throw new Exception('the require id not exist');
		}
		$home->delete();
		/**
		 * delete old gallery
		 */
		$home->gallery()->delete();
		$this->deleteHomeAttrs($home);
		$this->deleteRooms($home);
	}
}
