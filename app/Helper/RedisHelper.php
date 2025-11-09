<?php

namespace App\Helper;

use Illuminate\Support\Facades\Redis;
use JsonSerializable;

// https://laravel.com/docs/12.x/redis#interacting-with-redis
final class RedisHelper
{
	function __construct() {}

	/**
	 * @param string $key
	 * @param string|bool $value
	 * @return bool
	 */
	public static function setValue(string $key, $value): bool
	{
		try {
			Redis::set($key, $value);
			return true;
		} catch (\Throwable $th) {
			//throw $th;
		}
		return false;
	}

	/**
	 * @param string $key
	 * @return string|null
	 */
	public static function getValue(string $key)
	{
		try {
			return Redis::get($key);
		} catch (\Throwable $th) {
			//throw $th;
		}
		return null;
	}
}
