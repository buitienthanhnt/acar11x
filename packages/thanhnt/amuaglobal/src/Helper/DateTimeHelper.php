<?php

namespace Thanhnt\Amuaglobal\Helper;

final class DateTimeHelper
{
	/**
	 * sort array string date time for asc|desc
	 * @param string[] $dateArray [2025-12-12, 2025-12-15, ....]
	 * @param string $type
	 * @return string[]
	 */
	public function sortArrayDateString(array $dateArray, string $type = 'asc')
	{
		// sort by usort and callback of object function: use an array with 2 param [$this instance and, function name] 
		usort($dateArray, [$this, $type === 'asc' ? 'compareDatesAsc' : "compareDatesDesc"]);
		return $dateArray;
	}

	/**
	 * sort  desc
	 * @param string $date1
	 * @param string $date2
	 * @return int
	 */
	protected function compareDatesDesc(string $date1, string $date2)
	{
		// return number
		return strtotime($date2) - strtotime($date1); // Note the order change
	}

	/**
	 * sort asc
	 * @param string $date1
	 * @param string $date2
	 * @return int
	 */
	protected function compareDatesAsc(string $date1, string $date2)
	{
		// return number
		return strtotime($date1) - strtotime($date2); // Note the order change
	}
	/**
	 * convert string date to format date
	 * @param array $dateStrings
	 * @param string $format
	 * @return string[]
	 */
	public function getListDates(array $dateStrings, string $format = 'Y-m-d')
	{
		if (config('amuaglobal.mode', 'list_date') === 'date_range') {
			// https://code.mu/vi/php/manual/time/oop/DatePeriod/
			// https://www.php.net/manual/en/class.dateperiod.php
			$dateStrings = new \DatePeriod(
				new \DateTime(min($dateStrings)),
				new \DateInterval('P1D'),
				(new \DateTime(max($dateStrings)))->modify('+1 day') // include end date
			);
			/**
			 * Hàm `iterator_to_array()` là một hàm tích hợp sẵn trong PHP được sử dụng để sao chép đối tượng lặp 
			 * (ví dụ: các đối tượng triển khai giao diện `Iterator` hoặc `IteratorAggregate`) vào một mảng. 
			 * Trình lặp là một đối tượng cho phép bạn lặp qua một tập hợp các giá trị từng giá trị một mà không cần biết cấu trúc dữ liệu cơ bản.
			 */
			$dateStrings = array_map(function ($date) {
				/** @var \DateTime $date */
				return $date->format('Y-m-d');
			}, iterator_to_array($dateStrings));
		}

		return array_map(function ($date) use ($format) {
			return $this->formatDateString($date, $format);
		}, $dateStrings);
	}

	protected function formatDateString(string $date, string $format = 'Y-m-d')
	{
		$dateTime = new \DateTime($date);
		return $dateTime->format($format);
	}
}
