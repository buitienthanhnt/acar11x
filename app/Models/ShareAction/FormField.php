<?php

namespace App\Models\ShareAction;

/**
 * all of model use the trait must be defined variable: $formFields = self::FORM_FIELDS;
 * for function: formField can be work auto fill fields.
 * use for model to get form data.
 */
trait FormField
{
	/**
	 * get form data for model(filled form value)
	 * support for create and edit form auto.
	 */
	public function formField(): array
	{
		$formData = $this->formFields;
		if ($this->getKey()) {
			foreach ($formData as $key => &$value) {
				$value['value'] = $this->{$key};
			}
		}
		return array_values($formData);
	}

	/**
	 * format data before fill to object
	 * thjs function not use now because laravel has mass-assignment(cho phép gán hàng loạt tự động)
	 * https://laravel.com/docs/12.x/eloquent#allowing-mass-assignment
	 * dùng trong trường hợp tạo mới đối tượng thông qua factory do nó không sử dụng được: mass-assignment trong khi nhiều trường không có giá trị mặc định
	 * Page::factory()->create($this->defaultModel->fillData($request->toArray()))->save();
	 * @param array $formInput
	 * @return array
	 */
	public function fillData(array $formInput = []): array
	{
		/**
		 * format data for model factory by : FILLED_FILEDS và $data input:
		 * $key: FILLED_FILEDS = [self::TITLE, self::ACTIVE, self::ALIAS, self::IMAGE_PATH, self::DESCRIPTION, self::WRITER];
		 * $data:['TITLE' => 'view','ACTIVE' => '/detail/','ALIAS' => '','DESCRIPTION' => 'preview',]
		 */
		$modelData = array_intersect_key( // so sánh 2 mảng và trả về mảng có khóa chung
			$formInput,
			array_flip($this->fillable) // đảo ngược khóa và gía trị. trong mảng 1 chiều nó sẽ nhận giá trị là index số : 0,1,2,3
		);
		return $modelData;
	}
}
