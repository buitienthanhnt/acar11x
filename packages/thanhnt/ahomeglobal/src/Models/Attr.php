<?php

namespace Thanhnt\Ahomeglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Ahomeglobal\Models\Types\AttrInterface;

class Attr extends Model implements AttrInterface
{
    use SoftDeletes;

    // define table name)
    protected $table = self::TABLE_NAME;
    protected $fillable = self::FILLED_FIELDS; 
}