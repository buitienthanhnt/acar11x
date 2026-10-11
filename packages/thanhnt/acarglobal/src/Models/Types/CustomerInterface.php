<?php

namespace Thanhnt\Acarglobal\Models\Types;

interface CustomerInterface
{
	const TABLE_NAME = 'acar_customers';

	const ID = 'id';
	const NAME = 'name';
	const PHONE = 'phone';
	const ADDRESS = 'address';
	const COMPANY = 'company';

	const FILLED_FIELDS = [
		self::NAME,
		self::PHONE,
		self::ADDRESS,
		self::COMPANY,
	];

	const HIDDEN_FIELDS = ['updated_at', 'deleted_at', 'created_at'];

	const FORM_FIELDS = [
		['name' => self::NAME, 'type' => 'text', 'label' => 'Tên khách hàng', 'required' => true],
		['name' => self::PHONE, 'type' => 'text', 'label' => 'Số điện thoại',],
		['name' => self::ADDRESS, 'type' => 'text', 'label' => 'Địa chỉ',],
		['name' => self::COMPANY, 'type' => 'text', 'label' => 'Công ty',],
	];
}
