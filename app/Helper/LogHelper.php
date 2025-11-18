<?php

namespace App\Helper;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LogHelper
{
	/**
	 * cmd add permission for storage/logs folder: sudo chmod -R 777 storage/logs
	 * log message to daily log file
	 * clear storage/thanhnt folder:  sudo rm -rf storage/logs/thanhnt
	 * @param string $message
	 * @param string $type log type: info, warning, error, debug(vendor/psr/log/src/LoggerInterface.php)
	 * @param array $context thông thường là 1 mảng data truyền vào lưu dưới dạng json string.
	 */
	public function logToDay(string $message, string $type = 'info', array $context = []): void
	{
		$now = new Carbon();
		$day = $now->format('Y-m-d');

		/**
		 * @see config/logging.php 'channels';
		 */
		Log::build([
			'driver' => 'single',
			'path' => storage_path('logs/thanhnt/' . $day . '.log'),
		])->{$type}(message: $message, context: $context);
	}

	/**
	 * log message to tha file
	 * clear storage/thanhnt folder:  sudo rm -rf storage/logs/thanhnt
	 * @param string $message
	 * @param string $type log type: info, warning, error, debug(vendor/psr/log/src/LoggerInterface.php)
	 * @param array $context
	 */
	public function logTha(string $message, string $type = 'info', array $context = []): void
	{
		Log::build([
			'driver' => 'single',
			'path' => storage_path('logs/thanhnt/tha.log'),
		])->{$type}(message: $message, context: $context);
	}

	/**
	 * log message to multi channel
	 * driver: single: là ghi ra 1 file tùy chỉnh
	 * driver: stack: là ghi ra file mặc định laravel.log được cấu hình trong config/logging.php 
	 * @param array $channels
	 * @param string $message
	 * @param string $type
	 * @param mixed $context
	 */
	public function logMultiChannel(string $message, array $channels = [],  string $type = 'info', $context = [])
	{
		/**
		 * create custom channel
		 */
		$channel = Log::build([
			'driver' => 'single',
			'path' =>  storage_path('logs/thanhnt/multi.log'),
		]);

		/**
		 * log to multi channel
		 */
		Log::stack([$channel, ...$channels])->{$type}(message: $message, context: $context);
	}
}
