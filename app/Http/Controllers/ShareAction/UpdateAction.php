<?php

namespace App\Http\Controllers\ShareAction;

use Illuminate\Http\Request;

trait UpdateAction
{
	/**
	 * @param int $id
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	function updateAction(int $id, Request $request)
	{
		$model = $this->defaultModel->find($id);
		$model->fill($request->toArray())->save();
		return redirect()->to($this->defaultModel::ROUTE_PREFIX)->with('message', 'updated success the field!');
	}
}
