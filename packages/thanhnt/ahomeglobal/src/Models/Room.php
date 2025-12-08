<?php

namespace Thanhnt\Ahomeglobal\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Ahomeglobal\Database\Factories\RoomFactory;
use Thanhnt\Ahomeglobal\Models\Types\OrderInterface;
use Thanhnt\Ahomeglobal\Models\Types\OrderTimeInterface;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

#[UseFactory(RoomFactory::class)] // define class attribute by using #(https://www.php.net/manual/en/language.attributes.overview.php)
class Room extends Model implements RoomInterface
{
    use HasFactory;
    use SoftDeletes;

    protected $hidden = self::HIDDEN_FIELDS;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['booked_dates'];

    /**
     * link to home of the room
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function home(): BelongsTo
    {
        return $this->belongsTo(Home::class, self::HOME_ID);
    }

    /**
     * links to list order of the room
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        // https://stackoverflow.com/questions/36249828/how-to-search-json-array-in-mysql
        return $this->hasMany(Order::class, Order::ROOM_ID, self::ID)->where(OrderInterface::DATE_FROM, '>=', substr(Carbon::now()->toISOString(), 0, 10));
    }

    /**
     * get list dated has booked in the future of the room.
     */
    protected function bookedDates(): Attribute
    {
        return new Attribute(
            get: fn() => OrderTime::whereNowOrFuture(OrderTime::DATE)
                ->whereJsonContains(OrderTimeInterface::ROOM_IDS, $this->id)
                ->select(OrderTimeInterface::DATE)
                ->get()
                ->pluck(OrderTimeInterface::DATE)
                ->toArray(),
        );
    }
}
