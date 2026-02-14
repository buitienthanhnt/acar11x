<?php

namespace Thanhnt\Ahomeglobal\Models;

use App\Models\ShareAction\ImagePathAttrModel;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Thanhnt\Ahomeglobal\Database\Factories\HomeFactory;
use Thanhnt\Ahomeglobal\Models\Types\HomeInterface;
use Thanhnt\Ahomeglobal\Models\Types\OrderTimeInterface;
use Thanhnt\Amuaglobal\Models\Attr;
use Thanhnt\Amuaglobal\Models\Gallery;

/**
 * create by cmd: php artisan acar:make-model Home ahomeglobal
 */
#[UseFactory(HomeFactory::class)]
class Home extends Model implements HomeInterface
{
    use HasFactory;
    use SoftDeletes;
    use ImagePathAttrModel;

    protected $hidden = self::HIDDEN_FIELDS;

    protected $fillable = self::FILLED_FILEDS;
    /**
     * Get a new query builder for the model's table.
     * define for replace * to select list defualt attrs
     *
     * @param  bool  $exceptDeleted
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newModelQuery()
    {
        return parent::newModelQuery()->select(self::DEFAULT_SELECT);
    }

    /**
     * links to list rooms of the home
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, Room::HOME_ID, self::ID);
    }

    /**
     * links to list orders of the home
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, Order::HOME_ID, self::ID);
    }

    /**
     * link to list booked time of the home
     */
    public function orderTimes(): HasMany
    {
        return $this->hasMany(OrderTime::class, OrderTimeInterface::HOME_ID, self::ID);
    }

    /**
     * link to list attrribute(Attr model) of the home
     */
    public function attr(): HasMany
    {
        return $this->hasMany(Attr::class, Attr::SOURCE_ID, self::ID)->where(Attr::TYPE, 'home');
    }

    /**
     * link to list gallery of the home
     */
    public function gallery(): HasMany
    {
        return $this->hasMany(Gallery::class, Gallery::SOURCE_ID, self::ID)->where(Gallery::TYPE, 'home');
    }

    /**
     * format alias path of model if input value null object will use value of title
     * https://laravel.com/docs/12.x/eloquent-mutators#mutating-multiple-attributes
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function alias(): Attribute
    {
        return Attribute::make(
            set: fn($value, $attributes) => $value ?: Str::slug($attributes[self::NAME] ?? ''),
        );
    }
}
