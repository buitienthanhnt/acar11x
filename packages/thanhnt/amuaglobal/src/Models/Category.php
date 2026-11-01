<?php

namespace Thanhnt\Amuaglobal\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Amuaglobal\Models\Types\CategoryInterface;
use Thanhnt\Amuaglobal\Models\Casts\{ActiveAttrModel, AliasAttrModel, ImageAttrModel};
use Thanhnt\Amuaglobal\Models\Scopes\ActiveScope;
use Thanhnt\Amuaglobal\Observers\CategoryObserver;

/**
 * apply global scope for model
 */
#[ScopedBy([ActiveScope::class])]
#[ObservedBy([CategoryObserver::class])]
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
     * link category to childrent
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Category::class, self::PARENT_ID, self::ID);
    }

    /**
     * 2. QUAN HỆ ĐỆ QUY: Lấy vô hạn cấp con (Con của con của con...)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childrens()
    {
        // Gọi lại chính quan hệ children và nạp đệ quy nó qua 'with'
        return $this->children()->with(self::R_CHILDRENS);
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
