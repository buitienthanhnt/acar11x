<?php

namespace Thanhnt\Amuaglobal\Helper;

final class ModelHelper
{
	/**
	 * format array input model data for massing attribute(case: $data value more than $fields count)
	 * @param array $fields ['a', 'b', 'c', ....]
	 * @param array $data   ['a' => 'val', 'b' => 'val', 'c' => 'val', 'd' => 'val' ...]
	 * @return array
	 */
	public function massDataAttribute(array $fields, array $data): array
	{
		if (empty($fields) || empty($data)) {
			return [];
		}

		/**
		 * format data for model factory by : FILLED_FILEDS và $data input:
		 * $key: FILLED_FILEDS = [self::TITLE, self::ACTIVE, self::ALIAS, self::IMAGE_PATH, self::DESCRIPTION, self::WRITER];
		 * $data:['TITLE' => 'view','ACTIVE' => '/detail/','ALIAS' => '','DESCRIPTION' => 'preview',]
		 */
		return array_intersect_key( // so sánh 2 mảng và trả về mảng có khóa chung
			$data,
			array_flip($fields) // đảo ngược khóa và gía trị. trong mảng 1 chiều nó sẽ nhận giá trị là index số : 0,1,2,3
		);
	}

	/**
	 * format form fields attribute with data(add value to foem fields).
	 * @param array $fileds [[ 'name' => 'title', 'type' => 'text', ...], [...], ...]
	 * @param array{[string]: [string]} $data [key => value, ...]
	 * @return array
	 */
	public function formAttribute(array $fields, array $data): array
	{
		foreach ($fields as $key => &$value) {
			if (!isset($data[$key])) {
				continue;
			}
			$value['value'] = $data[$key];
		}
		return array_values($fields);
		return [];
	}
}
