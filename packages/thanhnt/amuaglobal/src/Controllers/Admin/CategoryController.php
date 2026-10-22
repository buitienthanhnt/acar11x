<?php

namespace Thanhnt\Amuaglobal\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Thanhnt\Amuaglobal\Actions\CategoryAction;
use Thanhnt\Amuaglobal\Api\CategoryApi;
use Thanhnt\Amuaglobal\Models\Category;
use Thanhnt\Amuaglobal\Models\Repository\CategoryRepository;

final class CategoryController extends Controller
{
	function __construct(
		protected CategoryAction $categoryAction,
		protected CategoryApi $categoryApi,
		protected CategoryRepository $categoryRepository,
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
			'categories' => Category::paginate(15),
		]);
	}

	/**
	 * create new category page view form
	 */
	public function createCategory()
	{
		$categoryTree = $this->categoryApi->categoryTree();
		// dd($categoryTree->toArray());
		return Inertia::render('Amuaglobal/Screens/Adminhtml/Category/CategoryCreate', [
			'fornFields' => [],
			'categoryTree' => $categoryTree,
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

	public function editCategory($id, Request $request)
	{
		$category = $this->categoryRepository->categoryDetail($id);
		return $category;
		return Inertia::render('');
	}

	public function updateCategory()
	{
		return redirect()->back();
	}
}
