<?php

namespace Thanhnt\Acarglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Thanhnt\Acarglobal\Models\Types\SalaryInterface;

final class Salary extends Model implements SalaryInterface
{
	protected $table = self::TABLE_NAME;

	protected $fillable = self::FILLED_FIELDS;
}
