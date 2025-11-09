<?php

namespace App\Models;

use App\Events\CategorySaved;
use App\Models\Scopes\ActiveScope;
use App\Models\ShareAction\ActiveAttrModel;
use App\Models\ShareAction\AliasAttrModel;
use App\Models\ShareAction\FormField;
use App\Models\ShareAction\ImageManualAttr;
use App\Models\Types\CategoryInterface;
use App\Models\Types\CommentInterface;
use App\Models\Types\ViewSourceInterface;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * define scope attribute.
 */
#[ScopedBy([ActiveScope::class])]
class Category extends Model implements CategoryInterface
{
    use HasFactory;
    use SoftDeletes;

    use ActiveAttrModel;
    use FormField;
    use ImageManualAttr;
    use AliasAttrModel;

    /**
     * define for event of model
     */
    protected $dispatchesEvents = [
        'saved' => CategorySaved::class
    ];

    /**
     * thuộc tính cần cho: FormField trait để lấy form update field.
     */
    protected $formFields = self::FORM_FIELDS;

    /**
     * define list attribute for mass assignable.
     * must be fibe for use: UpdateAction shareAction in controller
     */
    protected $fillable = self::FILLED_FILEDS;

    /**
     * define for hidden attributes of this model
     */
    protected $hidden = self::HIDDEN_FIELDS;

    /**
     * for alias share attribute(default define in ShareAction = TITLE)
     */
    const TITLE = self::NAME;

    /**
     * function: booted
     * define for events, listen of models
     */
    protected static function booted()
    {
        /**
         * define action after delete category
         * delete folder of category image path.
         */
        static::deleted(function (Category $category): void {
            /**
             * if null skip
             */
            if (!$category->{self::IMAGE_PATH}) {
                return;
            }
            /**
             * get path folder and delete it by Storage.
             */
            $dirPathCategory = 'categories/' . $category->id;
            Storage::deleteDirectory($dirPathCategory);
        });
    }

    /**
     * function return options value for select option field
     * the function must be static function because in interface form field define can not define model object.
     * @return array ['label' => string, 'value' => number][]
     */
    public static function parentOptions(): array
    {
        return (self::getCategoryTree());
    }

    /**
     * get flat category tree.
     * @param int $parentId
     * @param string $prefix
     * @param  array $allCategories
     * @return array
     */
    public static function getCategoryTree(int $parentId = 0, string $prefix = '__', array &$allCategories = []): array
    {
        /**
         * ->where(CategoryInterface::ACTIVE, '=', true) using adminhtml env not working with activeScope global. 
         */
        foreach (
            Category::select(CategoryInterface::ID, CategoryInterface::PARENT, CategoryInterface::NAME)
                ->where(self::PARENT, '=', $parentId)
                ->where(CategoryInterface::ACTIVE, '=', true)
                ->get() as $value
        ) {
            $data['label'] = ($prefix === '__' ? '' : $prefix) . $value->{CategoryInterface::NAME};
            $data['value'] = $value->{CategoryInterface::ID};
            $data['parent'] = $value->{CategoryInterface::PARENT};
            $allCategories[] = $data;
            if (Category::where(self::PARENT, '=', $value->{CategoryInterface::ID})->where(CategoryInterface::ACTIVE, '=', true)->count()) {
                self::getCategoryTree($value->{CategoryInterface::ID}, allCategories: $allCategories, prefix: $prefix . $prefix);
            }
        }
        return $allCategories;
    }

    /**
     * format for input, output attribute: parent
     */
    public function parent(): Attribute
    {
        return Attribute::make(set: function (int|null $input) {
            return is_numeric($input) ? $input : 0;
        });
    }

    /**
     * liên kết tới danh sách bài viết:
     * 1:khai báo model liên kết cuối
     * 2: khai báo bảng trung gian.
     */
    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'page_categories');
    }

    /**
     * return comment of category
     */
    public function comments(): HasMany
    {
        /**
         * target-class, khóa phụ của target-class, khóa chính của class hiện tại.
         */
        return $this->hasMany(Comment::class, CommentInterface::TARGET_ID, self::ID)->where(CommentInterface::TYPE, 'category');
    }

    /**
     * return view source info of the category.
     */
    public function source(): HasOne
    {
        return $this->hasOne(ViewSource::class, ViewSourceInterface::TARGET_ID, self::ID)->where(ViewSourceInterface::TYPE, self::MODEL_TYPE);
    }
}

// php artisan make:model category.

// INFO  Model [app/Models/Category.php] created successfully.
// INFO  Factory [database/factories/CategoryFactory.php] created successfully.
// INFO  Migration [database/migrations/2025_08_19_150247_create_categories_table.php] created successfully.
// INFO  Seeder [database/seeders/CategorySeeder.php] created successfully.
// INFO  Request [app/Http/Requests/StoreCategoryRequest.php] created successfully.
// INFO  Request [app/Http/Requests/UpdateCategoryRequest.php] created successfully.
// INFO  Controller [app/Http/Controllers/CategoryController.php] created successfully.
// INFO  Policy [app/Policies/CategoryPolicy.php] created successfully.
