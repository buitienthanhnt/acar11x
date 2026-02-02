<?php

namespace Thanhnt\Ahomeglobal\Models\Types;

interface HomeInterface
{
    const TABLE_NAME = 'homes';

    const ID = 'id';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const DISTRICT = 'district';
    const IMAGE_PATH = 'image_path';
    const ADDRESS = 'address';
    const ALIAS = 'alias';

    // define relationship conditions
    const ROOMS = 'rooms';
    const ATTR = 'attr';
    const ORDER_TIMES = 'orderTimes';
    const ORDERS = 'orders';
    const GALLERY = 'gallery';

    public function rooms(): \Illuminate\Database\Eloquent\Relations\HasMany;
    public function attr(): \Illuminate\Database\Eloquent\Relations\HasMany;
    public function orderTimes(): \Illuminate\Database\Eloquent\Relations\HasMany;
    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany;
    public function gallery(): \Illuminate\Database\Eloquent\Relations\HasMany;

    /**
     * khai báo thuộc tính biểu mẫu để tạo form.
     * define: [string $key => ['key' => string, 'type' => string, 'label ?=> string]][]
     */
    const ATTR_G_MAP = 'g_map';
    const ATTR_LOCATION = 'location';
    const ATTR_RATE = 'rate';

    const DEFAULT_SELECT = [self::ID, self::NAME, self::DESCRIPTION, self::DISTRICT, self::IMAGE_PATH];

    /**
     * define main form fields for Home model
     */
    const FORM_FIELDS = [
        self::NAME => ['key' => self::NAME, 'type' => FormInterface::TYPE_TEXT, 'label' => 'tên khách sạn', 'required' => true,],
        self::ALIAS => ['key' => self::ALIAS, 'type' => FormInterface::TYPE_TEXT, 'label' => 'khóa định danh',],
        self::DESCRIPTION => ['key' => self::DESCRIPTION, 'type' => FormInterface::TYPE_TEXTAREA, 'label' => 'mô tả chung',],
        self::IMAGE_PATH => ['key' => self::IMAGE_PATH, 'type' => FormInterface::TYPE_IMAGE_CHOOSE, 'label' => 'Ảnh đại diện',],
        self::DISTRICT => ['key' => self::DISTRICT, 'type' => FormInterface::TYPE_TEXTAREA, 'label' => 'địa chỉ',],
        self::ADDRESS => ['key' => self::ADDRESS, 'type' => FormInterface::TYPE_TEXT, 'label' => 'Vị trí',],
        self::GALLERY => ['key' => self::GALLERY, 'type' => FormInterface::TYPE_IMAGE_CHOOSE, 'label' => 'Ảnh chi tiết', 'placeholder' => 'chọn ảnh'],
    ];

    /**
     * define custom attributes for Home model
     * khai báo thuộc tính biểu mẫu tùy chỉnh để tạo form.
     */
    const CUSTOM_ATTRS = [
        self::ATTR_G_MAP => ['key' => self::ATTR_G_MAP, 'type' => FormInterface::TYPE_TEXT, 'label' => 'địa chỉ google(lat-lng)', 'placeholder' => 'ex: 20.98245366081677, 105.81097140400442', 'show_filter' => false],
        self::ATTR_LOCATION => ['key' => self::ATTR_LOCATION, 'type' => FormInterface::TYPE_TEXT, 'label' => 'bản đồ', 'placeholder' => 'bản đồ'],
        self::ATTR_RATE => ['key' => self::ATTR_RATE, 'type' => FormInterface::TYPE_NUMBER, 'label' => 'đánh giá', 'placeholder' => 'đánh giá'],
    ];

    /**
     * khai báo danh sách các thuộc tính được gán hàng loạt.
     */
    const FILLED_FILEDS = [self::NAME, self::ALIAS, self::DESCRIPTION, self::IMAGE_PATH, self::DISTRICT,  self::ADDRESS,];

    /**
     * define hidden fields
     */
    const HIDDEN_FIELDS = ['created_at', 'updated_at', 'deleted_at',];

    const PREFIX = 'home';
    const ROUTE_PREFIX = ADMIN_PREFIX . '/ahome';
}
