<?php

namespace Thanhnt\Acarglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Thanhnt\Acarglobal\Models\Types\EmployeeInerface;

final class Employee extends Model implements EmployeeInerface
{
	
	protected $table = self::TABLE_NAME;

	protected $fillable = self::FILLED_FIELDS;
	

}
