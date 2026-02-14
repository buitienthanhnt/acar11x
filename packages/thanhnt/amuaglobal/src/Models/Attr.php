<?php

namespace Thanhnt\Amuaglobal\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Amuaglobal\Database\Factories\AttrFactory;
use Thanhnt\Amuaglobal\Models\Types\AttrInterface;

#[UseFactory(AttrFactory::class)]
class Attr extends Model implements AttrInterface
{
	use SoftDeletes;
	use HasFactory;

	// define table name)
	protected $table = self::TABLE_NAME;
	protected $fillable = self::FILLED_FIELDS;

	public $timestamps = false;
}
