<?php

namespace Thanhnt\Acarglobal\Models\Types;

interface CarInterface
{
	const TABLE_NAME = 'acar_cars';
	const ID = 'id';
	const KEY = 'key'; // mã xe(biển số)
	const SUSPENSION = 'suspension'; // hãng xe
	const TYPE = 'type'; // dòng xe
	const KM = 'km'; // số km đã đi
	const YEAR = 'year'; // năm sản xuất
	const VIN = 'vin'; // mã VIN

	const CUSTOMER = 'customer';
	const PHONE = 'phone';
	const ADDRESS = 'address';

	const FILLED_FIELDS = [
		self::KEY,
		self::SUSPENSION,
		self::TYPE,
		self::KM,
		self::YEAR,
		self::VIN,
		self::CUSTOMER,
		self::PHONE,
		self::ADDRESS,
	];

	const HIDDEN_FIELDS = ['updated_at', 'deleted_at'];

	const FORM_FIELDS = [
		['name' => self::KEY, 'type' => 'text', 'label' => 'Biển số', 'required' => true],
		['name' => self::SUSPENSION, 'type' => 'text', 'label' => 'hãng xe(vd: Audi)',],
		['name' => self::TYPE, 'type' => 'text', 'label' => 'dòng xe(vd: Q7)',],
		// ['name' => self::KM, 'type' => 'number', 'label' => 'số km đã đi',],
		['name' => self::YEAR, 'type' => 'time', 'label' => 'năm sản xuất',],
		['name' => self::VIN, 'type' => 'text', 'label' => 'mã VIN',],
		['name' => self::CUSTOMER, 'type' => 'text', 'label' => 'khách hàng',],
		['name' => self::PHONE, 'type' => 'text', 'label' => 'sđt',],
		['name' => self::ADDRESS, 'type' => 'text', 'label' => 'địa chỉ',],
	];
}
