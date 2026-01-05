<?php

namespace Thanhnt\Abookglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Abookglobal\Models\Types\PublisherInterface;

final class Publisher extends Model implements PublisherInterface
{
	use SoftDeletes;

	protected $table = self::TABLE_NAME;
	protected $primaryKey = self::ID;

	public static function publisherOptions(): array
	{
		return self::all()->toArray();
	}
}
