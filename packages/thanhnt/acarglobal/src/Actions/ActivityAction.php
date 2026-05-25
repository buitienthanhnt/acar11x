<?php

namespace Thanhnt\Acarglobal\Actions;

use Thanhnt\Acarglobal\Helper\FormData;
use Thanhnt\Acarglobal\Models\Activity;
use Thanhnt\Acarglobal\Models\Types\ActivityInterface;

final class ActivityAction
{

	use FormData;

	public function __construct()
	{
		// throw new \Exception('Not implemented');
	}

	public function onAddActivity(array $data)
	{
		$formData = $this->formData(ActivityInterface::FORM_FIELDS, $data);
		// $formData[ActivityInterface::STATUS] = ActivityInterface::STATUS_WAIT;
		Activity::create($formData + [
			ActivityInterface::PRODUCT_ID => $data['product']['id'] ?? null,
		]);
	}

	/**
	 * Xoa cong viec
	 * @param int $id
	 * @return \Thanhnt\Acarglobal\Models\Activity|null
	 */
	public function removeActivity(int $id)
	{
		$activity = Activity::find($id);
		$activity?->delete();
		return $activity;
	}
}
