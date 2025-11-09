<?php

namespace App\Models;

use App\Events\WriterSaved;
use App\Listeners\WriterSavedListen;
use App\Models\Scopes\ActiveScope;
use App\Models\ShareAction\ActiveAttrModel;
use App\Models\ShareAction\FormField;
use App\Models\ShareAction\ImageManualAttr;
use App\Models\Types\PageInterface;
use App\Models\Types\WriterInterface;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * define scope attribute.
 */
#[ScopedBy([ActiveScope::class])]
class Writer extends Model implements WriterInterface
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
    use ImageManualAttr;

    /**
     * khai báo danh sách các thuộc tính không được gán hàng loạt.
     */
    protected $guarded = [self::IMAGE_PATH];

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
     * define for hidden attributes of this model
     */
    protected $hidden = self::HIDDEN_FIELDS;

    /**
     * khai báo lắng nghe cho các sự kiện thực hiện với Model:
     * saved, updated, deleting, and deleted
     */
    protected $dispatchesEvents = [
        'saved' => WriterSaved::class,
    ];

    /**
     * khai báo sự kiện cho các hành động trong hàm booted.
     * bằng cách này có thể áp dụng hàm số closure trực tiếp;
     * ngoài ra có thể đăng ký trong biến: $dispatchesEvents bên trên
     */
    protected static function booted()
    {
        /**
         * register event listen for closure.
         */
        static::deleted(function (Writer $writer): void {
            if (!$writer->{self::IMAGE_PATH}) {
                return;
            }
            /**
             * thực hiện xóa thư mục ảnh của tác giả sau khi đã xóa thông tin trong database.
             * mặc dù trong database là xóa mềm nhưng ảnh vẫn xóa vật lý để không bị nặng lưu trữ.
             */
            $writerFolder = WriterSavedListen::SAVE_FOLDER . 'writers/' . $writer->id;
            Storage::deleteDirectory($writerFolder);
        });

        /**
         * define global scope for model.
         */
        static::addGlobalScope(new ActiveScope);
    }

    /**
     * one-to-many: https://laravel.com/docs/12.x/eloquent-relationships#one-to-many
     * khi goji: $this->page se tra ve 1 collection. Khac voi goi ham: $this->pages()
     * return page collection of writer
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        /**
         * 1: Class Model
         * 2: $foreignKey of Model Class var 1.
         * 3: $localKey: primary key of this Model
         */
        return $this->hasMany(Page::class, PageInterface::WRITER);
    }

    /**
     * return flat list collection of writer
     * @return \Illuminate\Support\Collection<TKey, TValue>
     * ['label' => string, 'value' => string][]
     */
    public static function writerOptions()
    {
        /**
         * where(active) because in admin env global scope active not work.
         */
        return Writer::where(WriterInterface::ACTIVE, '=', true)->get()->map(fn($item) => [
            'label' => $item->{self::NAME},
            'value' => $item->{self::ID},
        ]);
    }
}
