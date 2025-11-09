<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

final class CategoryApiController extends Controller
{
	/**
	 * @var \App\Models\Api\CategoryApi $categoryApi
	 */
	protected $categoryApi;

	function __construct(
		\App\Models\Api\CategoryApi $categoryApi,
	) {
		$this->categoryApi = $categoryApi;
	}

	/**
	 * get center categories with list pages.
	 */
	public function centerCategories()
	{
		return $this->categoryApi->centerCategories();
	}
}
