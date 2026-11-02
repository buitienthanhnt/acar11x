<?php

namespace Thanhnt\Amuaglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Thanhnt\Amuaglobal\Models\Casts\ImageAttrModel;
use Thanhnt\Amuaglobal\Models\Types\{GalleryInterface, ProductCategoryInterface, ProductInterface};

class Product extends Model implements ProductInterface
{
    use ImageAttrModel;

    /**
     * define table name
     */
    protected $table = self::TABLE_NAME;

    /**
     * define mass assignable(gán hàng loạt)
     */
    protected $fillable = self::FILLED_FILEDS;

    /**
     * links to galleries images
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function galleries()
    {
        /**
         * hasMany<model liên kết, khóa phụ bảng liên kết, khóa tham chiếu bảng hiện tại>
         */
        return $this->hasMany(Gallery::class, GalleryInterface::SOURCE_ID, self::ID)->where(GalleryInterface::TYPE, GalleryInterface::TYPE_PRODUCT);
    }

    /**
     * links to categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        /**
         * belongsToMany<
         * model liên kết,
         * bảng trung gian,
         * khóa tham chiếu bảng hiện tại trong bảng trung gian,
         * khóa tham chiếu bảng liên kết trong bảng trung gian
         * >
         */
        return $this->belongsToMany(Category::class, ProductCategoryInterface::TABLE_NAME, ProductCategoryInterface::PRODUCT_ID, ProductCategoryInterface::CATEGORY_ID);
    }
}
