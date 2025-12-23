<?php

namespace Thanhnt\Ahomeglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Thanhnt\Ahomeglobal\Models\Types\OrderDetailInterface;

/**
 * order detail 
 */
final class OrderDetail extends Model implements OrderDetailInterface
{

	protected $fillable = self::FILLED_FILEDS;
}
