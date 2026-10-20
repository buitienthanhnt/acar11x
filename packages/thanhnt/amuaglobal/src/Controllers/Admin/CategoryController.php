<?php

namespace Thanhnt\Amuaglobal\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Thanhnt\Amuaglobal\Actions\CategoryAction;
use Thanhnt\Amuaglobal\Models\Category;

final class CategoryController extends Controller
{
	function __construct(
		protected CategoryAction $categoryAction,
	) {
		// throw new \Exception('Not implemented');
	}
	/**
	 * show list of category
	 *
	 * @return void
	 */
	public function listCategory()
	{

		return Inertia::render('Amuaglobal/Screens/Adminhtml/Category/CategoryList', [
			'categories' => Category::paginate(12),
		]);
	}

	/**
	 * create new category page view form
	 */
	public function createCategory()
	{
		return Inertia::render('Amuaglobal/Screens/Adminhtml/Category/CategoryCreate', [
			'fornFields' => [],
		]);
	}

	/**
	 * register new category
	 *
	 * @return void
	 */
	public function storeCategory(Request $request)
	{
		$this->categoryAction->register($request);
		return redirect()->to('adminhtml/amua-category')->with('message', 'created new category!');
	}
}
