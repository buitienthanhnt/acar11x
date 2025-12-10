<?php

namespace Thanhnt\Ahomeglobal\Models\Types;

interface RoomInterface
{
	const TABLE_NAME = 'rooms';

	const ID = 'id';
	const TITLE = 'title';
	const DESCRIPTION = 'description';
	/**
	 * one, two, three, four, five, six
	 */
	const TYPE = 'type';
	const TYPE_VALUE = ['one', 'two', 'three', 'four', 'five', 'six', 'all'];
	const HOME_ID = 'home_id';
	const HIDDEN_FIELDS = ['created_at', 'updated_at', 'deleted_at', self::HOME_ID];

	/**
	 * khai báo thuộc tính biểu mẫu để tạo form.
	 * define: [string $key => ['key' => string, 'type' => string, 'label ?=> string]][]
	 */
	const ATTR_PRICE = 'price';
	const ATTR_BED = 'bed';
	const ATTR_PERSON = 'persion';
	const ATTR_RATE = 'rate';
	
	const CUSTOM_ATTRS = [
		self::ATTR_PRICE => ['key' => self::ATTR_PRICE, 'type' => FormInterface::TYPE_NUMBER, 'label' => 'Giá', 'required' => true,],
		self::ATTR_BED => ['key' => self::ATTR_BED, 'type' => FormInterface::TYPE_SELECT, 'label' => 'số giường', 'model' => \Thanhnt\AhomeGlobal\Models\Room::class, 'required' => true,],
		self::ATTR_PERSON => ['key' => self::ATTR_PERSON, 'type' => FormInterface::TYPE_NUMBER, 'label' => 'số lượng người',],
		self::ATTR_RATE => ['key' => self::ATTR_RATE, 'type' => FormInterface::TYPE_NUMBER, 'label' => 'đánh giá',],
	];

	/**
	 * khai báo danh sách các thuộc tính được gán hàng loạt.
	 */
	const FILLED_FILEDS = [];
}
