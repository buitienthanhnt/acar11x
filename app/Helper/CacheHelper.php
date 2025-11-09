<?php
namespace App\Helper;

use Illuminate\Support\Facades\Cache;

final class CacheHelper 
{
	public function __construct()
	{
		
	}

	/**
	 * @param string $key
	 * @param \Closure|\DateTimeInterface|\DateInterval|int|null $ttl đơn vị giây.
	 * @param \Closure $callback
	 * @return mixed
	 */
	public function saveAndReturn(string $key, \Closure|\DateTimeInterface|\DateInterval|int|null $ttl = 60*15, \Closure $callback) {
		return Cache::remember($key, $ttl, $callback);
	}

	/**
	 * 
	 */
	public function clear(string $key) {
		return Cache::forget($key);
	}
}
