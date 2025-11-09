<?php

namespace App\Models;

use App\Enums\CacheEnum;
use App\Events\PageSaved;
use App\Helper\RedisHelper;
use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\SortScope;
use App\Models\ShareAction\ActiveAttrModel;
use App\Models\ShareAction\AliasAttrModel;
use App\Models\ShareAction\FormField;
use App\Models\Types\CommentInterface;
use App\Models\Types\PageContentInterface;
use App\Models\Types\PageInterface;
use App\Models\Types\TagInterface;
use App\Models\Types\ViewSourceInterface;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

// khai báo các global scope áp dụng cho Model.
#[ScopedBy([ActiveScope::class])]
#[ScopedBy([SortScope::class])]
class Page extends Model implements PageInterface
{
    /**
     * default trait
     */
    use HasFactory;
    use SoftDeletes;

    /**
     * custom trait
     */
    use FormField;
    use ActiveAttrModel;
    use AliasAttrModel;

    /**
     * define listen action for Model.
     */
    protected $dispatchesEvents = [
        'saved' => PageSaved::class,
    ];

    /**
     * The attributes that aren't mass assignable.
     * các thuộc tính không cho phép gán hàng loạt.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     * các thuộc tính cho phép gán hàng loạt.
     *
     * @var array<int, string>
     */
    protected $fillable = self::FILLED_FILEDS;

    /**
     * thuộc tính cần cho: FormField trait để lấy form update field.
     */
    protected $formFields = self::FORM_FIELDS;

    /**
     * The database connection that should be used by the model(chỉ định loại csdl).
     *
     * @var string
     */
    // protected $connection = 'mysql';

    /**
     * gán các thuộc tính sẽ được ẩn khi truy vấn(không trả về trong collection).
     */
    protected $hidden = [self::CREATED_AT, self::DELETED_AT,];

    // protected $appends = ['info']; // Add the custom attribute here

    protected static function booted(): void
    {
        /**
         * define event for action after delete
         */
        static::deleted(function (Page $page) {
            /**
             * xóa liên kết danh mục tại bảng trung gian trong liên kết: (nhiều - nhiều)
             */
            $page->categories()->detach();
            /**
             * xóa liên kết nội dung bài viết trong liên kết (1 - nhiều)
             */
            $page->pageContents()->delete();
            /**
             * delete links of tags
             */
            $page->tags()->delete();

            /**
             * delete source info of the page.
             */
            $page->source()->delete();
        });

        /**
         * define event after page created.
         */
        static::created((function () : void {
            /**
             * clear top_page cache
             */
            Cache::forget(CacheEnum::TopPage->value);
        }));
    }

    /**
     * format page attribute.
     */
    public function above(): Attribute{
        return Attribute::make(
            set: fn (mixed $input) => !!$input,
        );
    }

    /**
     * return writer model of page(liên kết 1 - 1 nghịch đảo truyền vào class tới và khóa phụ).
     * function name same as const of interface.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function writer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Writer::class, self::WRITER);
    }

    /**
     * value for select option field.
     * hàm này sử dụng trong form field dạng select option
     * định dạng tên hàm: {tên key}.'Options'.
     * ví dụ: select field có name là writer thì tên hàm của model page là: 'writer'.'Options' = writerOptions
     */
    public static function writerOptions(): array
    {
        return Writer::writerOptions()->toArray();
    }

    /**
     * @return Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function imagePath(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return Attribute::make(
            get: function (string|null $value) {
                return $value ? (!isset(parse_url($value)['host']) ? asset($value) : $value) : '';
            },
            set: function (string|null $value) {
                // array [
                //   "scheme" => "http"
                //   "host" => "adoc.dev"
                //   "path" => "/storage/files/uploads/261479696_1820281014826477_6400419339212881138_n_084353.jpg"
                // ]
                $parseUrl = parse_url($value);   // support third url source
                return $value ? ($parseUrl['host'] === env('APP_HOST') ? parse_url($value)['path'] : $value) : null;
            },
        );
    }

    /**
     * get categories for page(many to many(liên kết nhiều - nhiều qua bảng trung gian))
     * https://laravel.com/docs/12.x/eloquent-relationships#many-to-many
     * không cần tạo Model trung gian mà chỉ cần bảng trung gian(tạo migration) thôi
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'page_categories',);
    }

    /**
     * return category list of the page
     */
    public function category(): Attribute
    {
        return Attribute::make(
            get: function () {
                return array_column($this->hasMany(PageCategory::class, 'page_id',)->get()->toArray(), 'category_id');
            }
        );
    }

    /**
     * liên kết 1 - nhiều tới page_contents.
     * get contents of the page.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pageContents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PageContent::class, PageContentInterface::PAGE_ID);
    }

    /**
     * return list tag of the page(1-to many).
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class, TagInterface::TARGET_ID);
    }

    /**
     * return flash category for select options.
     * [array(["scheme" => "string", "host" => "string", "port" => "int", "user" => "string", "pass" => "string", "query" => "string", "path" => "string", "fragment" => "string"])]
     * @return array
     */
    public static function categoryOptions()
    {
        return Category::parentOptions();
    }

    /**
     * return page info.
     * @/return array|null
     */
    // public function getInfoAttribute()
    // {
    //     $key = 'page:' . $this->{self::ID};
    //     return json_decode(RedisHelper::getValue($key) ?: '', true);
    // }

    /**
     * return list comments of the page.
     *  @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments() {
        return $this->hasMany(Comment::class, CommentInterface::TARGET_ID, PageInterface::ID)->where(CommentInterface::TYPE, 'page')->where(CommentInterface::ACTIVE, true);    
    }

    /**
     * get page view source info
     *  @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function source() : HasOne {
        return $this->hasOne(ViewSource::class, ViewSourceInterface::TARGET_ID, self::ID,)->where(ViewSourceInterface::TYPE, self::MODEL_TYPE);
    }
}
