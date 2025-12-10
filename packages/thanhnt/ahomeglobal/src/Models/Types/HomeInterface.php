<?php

namespace Thanhnt\Ahomeglobal\Models\Types;

interface HomeInterface{
	const TABLE_NAME = 'homes';

	const ID = 'id';
	const NAME = 'name';
	const DESCRIPTION = 'description'; 
	const DISTRICT = 'district';

	const HIDDEN_FIELDS = ['created_at', 'updated_at', 'deleted_at', ];

	 /**
     * khai báo thuộc tính biểu mẫu để tạo form.
     * define: [string $key => ['key' => string, 'type' => string, 'label ?=> string]][]
     */
	const ATTR_DISTRICT = 'district';
	const ATTR_LOCATION = 'location';
	const ATTR_RATE = 'rate';

    const CUSTOM_ATTRS = [
        self::ATTR_DISTRICT => ['key' => self::ATTR_DISTRICT, 'type' => FormInterface::TYPE_TEXT, 'label' => 'địa chỉ', 'required' => true],
        self::ATTR_LOCATION => ['key' => self::ATTR_LOCATION, 'type' => FormInterface::TYPE_TEXT, 'label' => 'bản đồ'],
        self::ATTR_RATE => ['key' => self::ATTR_RATE, 'type' => FormInterface::TYPE_NUMBER, 'label' => 'đánh giá'],
    ];

    /**
     * khai báo danh sách các thuộc tính được gán hàng loạt.
     */
    const FILLED_FILEDS = [];
}