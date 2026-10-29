<?php

namespace Thanhnt\Amuaglobal\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Thanhnt\Amuaglobal\Actions\CategoryAction;
use Thanhnt\Amuaglobal\Api\CategoryApi;
use Thanhnt\Amuaglobal\Helper\FormFieldHelper;
use Thanhnt\Amuaglobal\Models\Category;
use Thanhnt\Amuaglobal\Models\DataSource\CategorySource;
use Thanhnt\Amuaglobal\Models\Repository\CategoryRepository;
use Thanhnt\Amuaglobal\Models\Types\CategoryInterface;

final class CategoryController extends Controller
{
	function __construct(
		protected CategoryAction $categoryAction,
		protected CategoryApi $categoryApi,
		protected CategoryRepository $categoryRepository,
		protected CategorySource $categorySource,
		protected FormFieldHelper $formFieldHelper,
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
		return Inertia::render('Amuaglobal/Screens/Adminhtml/Category/CategoryCreate', [
			'formFields' => $this->formFieldHelper->formatFormFields(CategoryInterface::FORM_FIELDS,),
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

	public function editCategory(int $id, Request $request)
	{
		$category = $this->categoryRepository->categoryDetail($id);
		return Inertia::render('Amuaglobal/Screens/Adminhtml/Category/CategoryEdit', [
			'category' => $category,
			'formFields' => $this->formFieldHelper->formatFormFields(CategoryInterface::FORM_FIELDS, $category->toArray())
		]);
	}

	public function updateCategory(int $id, Request $request)
	{
		$this->categoryAction->update($id, $request->toArray());
		return redirect()->back();
	}
}
