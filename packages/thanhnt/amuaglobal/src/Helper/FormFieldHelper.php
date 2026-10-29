<?php

namespace Thanhnt\Amuaglobal\Helper;

use Thanhnt\Amuaglobal\Models\Types\FormInterface;

final class FormFieldHelper
{
	/**
	 * format form fields attribute with data(add value to foem fields).
	 * 
	 * @param array $formFields
	 * @param array $values
	 * @return array
	 */
	public function formatFormFields(array $formFields, $values = null): array
	{
		/**
		 * 
		 */
		foreach ($formFields as &$field) {
			/**
			 * check type of field and get data source
			 */
			switch ($field['type']) {
				/**
				 * type select option
				 */
				case FormInterface::TYPE_SELECT:
					/**
					 * 1. create data source
					 */
					$dataSource = app()->make($field['options_source']);
					/**
					 * 2. set options field
					 */
					$options = $dataSource->sourceModel();
					if (isset($values[$field['key']])) {
						foreach ($options as &$option) {
							if (is_array($values[$field['key']])) {
								if (in_array($option['value'], $values[$field['key']])) {
									$option['selected'] = true;
								}
							} else {
								if ($option['value'] === $values[$field['key']]) {
									$option['selected'] = true;
								}
							}
						}
					}
					$field['options'] = $options;
					/**
					 * 3. unset options source before return frontend value
					 */
					unset($field['options_source']);
					break;
				default:
					break;
			}

			/**
			 * input value if is exist
			 */
			if (isset($values[$field['key']])) {
				$field['value'] = $values[$field['key']];
			}
		}
		return $formFields;
	}
}
