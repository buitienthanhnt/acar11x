<?php

namespace Thanhnt\Ahomeglobal\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Ahomeglobal\Database\Factories\RoomFactory;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

#[UseFactory(RoomFactory::class)] // define class attribute by using #(https://www.php.net/manual/en/language.attributes.overview.php)
class Room extends Model implements RoomInterface
{
    use HasFactory;
    use SoftDeletes;

    protected $hidden = self::HIDDEN_FIELDS;
    
    /**
     * link to home of the room
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function home() : BelongsTo {
        return $this->belongsTo(Home::class, self::HOME_ID);
    }

    /**
     * links to list order of the room
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function  orders() : HasMany {
        return $this->hasMany(Order::class, Order::ROOM_ID, self::ID);
    }
}