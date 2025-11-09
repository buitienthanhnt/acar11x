<?php

namespace App\Http\Controllers\ShareAction;

use Illuminate\Http\Request;

trait DeleteAction
{

	/**
	 * the function use default model by defaultModel variable.
	 * so class use the trait must defind: defaultModel variable.
	 */
	public function deleteAction($id, Request $request)
	{
		$object = $this->defaultModel->find($id);
		$object->delete();

		/**
		 * trả về dạng json data.
		 */
		return response()->json([
			'message' => 'deleted for item',
			'code' => 200
		]);
	}
}
