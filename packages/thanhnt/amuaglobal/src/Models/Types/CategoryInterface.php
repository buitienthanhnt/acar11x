<?php

namespace Thanhnt\Amuaglobal\Models\Types;

interface CategoryInterface
{
	/**
	 * model table
	 */
	const TABLE_NAME = 'amua_categories';

	/**
	 * model attribute
	 */
	const ID = 'id';
	const NAME = 'name';
	const ALIAS = 'alias';
	const DESCRIPTION = 'description';
	const PARENT_ID = 'parent_id';
	const IMAGE = 'image';
	const ACTIVE = 'active';

	/**
	 * relation attribute
	 */
	const R_CHILDREN = 'children';
	const R_CHILDRENS = 'childrens';
	const R_PARENT = 'parent';
	const R_PARENTS = 'parents';
	const R_PRODUCTS = 'products';

	/**
	 * doc interface & abstract class:
	 * https://viblo.asia/p/phan-biet-giao-dien-interface-voi-lop-truu-tuong-abstract-class-trong-php-LzD5dL6W5jY
	 */

	/**
	 * define relation method link to category childrent
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function children();
	/**
	 * define relation method link to category childrent with loop sub category
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function childrens();
	/**
	 * define relation method link to category parent
	 * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function parent();
	/**
	 * define relation method link to category parent with loop parent category
	 * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function parents();
	/**
	 * define relation method link to product category
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function products();

	/**
	 * define massing field
	 */
	const FILLED_FIELDS = [self::ACTIVE, self::NAME, self::ALIAS, self::PARENT_ID, self::DESCRIPTION, self::IMAGE];

	/**
	 * define form fields
	 */
	const FORM_FIELDS = [
		['key' => self::NAME, 'type' => FormInterface::TYPE_TEXT, 'required' => true, 'label' => 'name', 'placeholder' => 'name'],
		['key' => self::ALIAS, 'type' => FormInterface::TYPE_TEXT, 'label' => 'alias'],
		['key' => self::DESCRIPTION, 'type' => FormInterface::TYPE_TEXTAREA, 'label' => 'description'],
		['key' => self::ACTIVE, 'type' => FormInterface::TYPE_BOOL, 'label' => 'active'],
		['key' => self::IMAGE, 'type' => FormInterface::TYPE_IMAGE_CHOOSE, 'label' => 'avatar'],
		['key' => self::PARENT_ID, 'type' => FormInterface::TYPE_SELECT, 'label' => 'parent category', 'options_source' => \Thanhnt\Amuaglobal\Models\DataSource\CategorySource::class, 'placeholder' => 'Select parent category'],
	];

	/**
	 * define hidden fields
	 */
	const HIDDEN_FIELDS = [self::PARENT_ID, 'created_at', 'updated_at', 'deleted_at'];
}
