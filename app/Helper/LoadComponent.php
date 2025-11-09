<?php

namespace App\Helper;

use App\Models\Api\CategoryApi;

final class LoadComponent
{
	/**
	 * @var \Illuminate\Http\Request $request
	 */
	protected $request;

	/**
	 * @var \App\Models\Api\PageApi $pageApi
	 */
	protected $pageApi;

	protected $categoryApi;

	public function __construct(
		\Illuminate\Http\Request $request,
		\App\Models\Api\PageApi $pageApi,
		CategoryApi $categoryApi,
	)
	{
		$this->request = $request;
		$this->pageApi = $pageApi;
		$this->categoryApi = $categoryApi;
	}
	
}
