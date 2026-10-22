<?php

namespace Thanhnt\Amuaglobal\Models;

use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Thanhnt\Amuaglobal\Models\Types\CategoryInterface;
use Thanhnt\Amuaglobal\Models\Casts\{ActiveAttrModel, AliasAttrModel, ImageAttrModel};
use Thanhnt\Amuaglobal\Models\Scopes\ActiveScope;

/**
 * apply global scope for model 
 */
#[ScopedBy([ActiveScope::class])]
final class Category extends Model implements CategoryInterface
{
	use ActiveAttrModel;
	use AliasAttrModel;
	use ImageAttrModel;

	protected $table = self::TABLE_NAME;
	protected $fillable = self::FILLED_FIELDS;
	protected $hidden = self::HIDDEN_FIELDS;

	public static function categoryOptions()
	{
		$categories = Category::where(self::PARENT_ID, 0)->with('childrents');
		return $categories;
	}

	/**
	 * link category to childrent
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function childrent()
	{
		return $this->hasMany(Category::class, self::PARENT_ID, self::ID);
	}

	/**
	 * 2. QUAN HỆ ĐỆ QUY: Lấy vô hạn cấp con (Con của con của con...)
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function childrents()
	{
		// Gọi lại chính quan hệ children và nạp đệ quy nó qua 'with'
		return $this->childrent()->with('childrents');
	}

	/**
	 * link category to parent
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function parent()
	{
		return $this->belongsTo(Category::class, self::PARENT_ID, self::ID);
	}

	/**
	 * (Tùy chọn) Nếu muốn đi ngược từ con lên vô hạn cấp cha
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function parents()
	{
		return $this->parent()->with('parents');
	}

	/**
	 * link category to products
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function products()
	{
		return $this->belongsToMany(Product::class, 'amua_product_categories', 'category_id', 'product_id');
	}
}
