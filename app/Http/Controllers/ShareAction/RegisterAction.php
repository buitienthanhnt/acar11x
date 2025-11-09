<?php

namespace App\Http\Controllers\ShareAction;

use Illuminate\Http\Request;

trait RegisterAction
{
	function registerAction(Request $request)
	{
		/**
		 * format data for model factory by : FILLED_FILEDS và $data input:
		 * $key: FILLED_FILEDS = [self::TITLE, self::ACTIVE, self::ALIAS, self::IMAGE_PATH, self::DESCRIPTION, self::WRITER];
		 * $data:['TITLE' => 'view','ACTIVE' => '/detail/','ALIAS' => '','DESCRIPTION' => 'preview',]
		 */
		$modelData = array_intersect_key( // so sánh 2 mảng và trả về mảng có khóa chung
			$request->toArray(),
			array_flip($this->defaultModel::FILLED_FILEDS) // đảo ngược khóa và gía trị. trong mảng 1 chiều nó sẽ nhận giá trị là index số : 0,1,2,3
		);
		$this->defaultModel->factory()->create($modelData)->save();
		return redirect()->to($this->defaultModel::ROUTE_PREFIX)->with('message', 'add success new row!');
	}
}
