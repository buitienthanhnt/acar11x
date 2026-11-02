<?php

namespace Thanhnt\Amuaglobal\Models;

use Illuminate\Database\Eloquent\Attributes\{ObservedBy, ScopedBy};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Amuaglobal\Models\Types\CategoryInterface;
use Thanhnt\Amuaglobal\Models\Casts\{ActiveAttrModel, AliasAttrModel, ImageAttrModel};
use Thanhnt\Amuaglobal\Models\Scopes\ActiveScope;
use Thanhnt\Amuaglobal\Models\Types\ProductCategoryInterface;
use Thanhnt\Amuaglobal\Observers\CategoryObserver;

/**
 * apply global scope for model
 */
#[ScopedBy([ActiveScope::class])]
#[ObservedBy([CategoryObserver::class])] // obserber for model action delete, create, update
final class Category extends Model implements CategoryInterface
{
    use SoftDeletes;
    use ActiveAttrModel;
    use AliasAttrModel;
    use ImageAttrModel;

    protected $table = self::TABLE_NAME;
    protected $fillable = self::FILLED_FIELDS;
    protected $hidden = self::HIDDEN_FIELDS;

    /**
     * define local event of category
     *
     * @var array
     */
    protected $dispatchesEvents = [
        // 'deleted' => CategoryDeleted::class,  // event when category deleted
    ];

    /**
     * link category to childrent(liên kết danh mục con)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Category::class, self::PARENT_ID, self::ID);
    }

    /**
     * QUAN HỆ ĐỆ QUY: Lấy vô hạn cấp con (Con của con của con...)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childrens()
    {
        // Gọi lại chính quan hệ children và nạp đệ quy nó qua 'with'
        return $this->children()->with(self::R_CHILDRENS);
    }

    /**
     * link category to parent(liên kết danh mục cha)
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
        /**
         * belongsToMany<
         * model liên kết,
         * bảng trung gian,
         * khóa tham chiếu bảng hiện tại trong bảng trung gian,
         * khóa tham chiếu bảng liên kết trong bảng trung gian
         * >
         */
        return $this->belongsToMany(Product::class, ProductCategoryInterface::TABLE_NAME, ProductCategoryInterface::CATEGORY_ID, ProductCategoryInterface::PRODUCT_ID);
    }
}
