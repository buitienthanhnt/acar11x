<?php

namespace Thanhnt\Ahomeglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Thanhnt\Ahomeglobal\Models\Types\AttrInterface;

class Attr extends Model implements AttrInterface
{
    protected $table = self::TABLE_NAME;
    
}