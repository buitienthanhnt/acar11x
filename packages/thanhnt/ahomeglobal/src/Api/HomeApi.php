<?php
namespace Thanhnt\Ahomeglobal\Api;

use Thanhnt\Ahomeglobal\Models\Home;

final class HomeApi
{
	public function __construct(
		protected Home $home
	)
	{
		// throw new \Exception('Not implemented');
	}
	/**
	 * @param int $homeId
	 * @return \Thanhnt\Ahomeglobal\Models\Home
	 */
	public function getHomeDetail(int $homeId) {
		return $this->home->with('rooms')->find($homeId);
	}
}
