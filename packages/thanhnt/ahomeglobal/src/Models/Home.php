<?php

namespace Thanhnt\Ahomeglobal\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Ahomeglobal\Database\Factories\HomeFactory;
use Thanhnt\Ahomeglobal\Models\Types\HomeInterface;

/**
 * create by cmd: php artisan acar:make-model Home ahomeglobal
 */
#[UseFactory(HomeFactory::class)]
class Home extends Model implements HomeInterface
{
    use HasFactory;
    use SoftDeletes;

    /**
     * links to list rooms of the home
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms() : HasMany {
        return $this->hasMany(Room::class, Room::HOME_ID, self::ID);
    }

    /**
     * links to list orders of the home
     */
    public function orders() : HasMany {
        return $this->hasMany(Order::class, Order::HOME_ID, self::ID);
    }
}