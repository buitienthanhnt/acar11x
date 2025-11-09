<?php

namespace App\Http\Controllers\AdminHtml;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ShareAction\DeleteAction;
use App\Http\Controllers\ShareAction\RegisterAction;
use App\Http\Controllers\ShareAction\UpdateAction;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Api\CategoryApi;
use App\Models\Category;
use App\Models\Types\CategoryInterface;

class CategoryController extends Controller
{

    use UpdateAction;
    use DeleteAction;
    use RegisterAction;

    protected $defaultModel;
    protected $categoryApi;

    function __construct(
        Category $category,
        CategoryApi $categoryApi,
    ) {
        $this->defaultModel = $category;
        $this->categoryApi = $categoryApi;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = [
            CategoryInterface::ID,
            CategoryInterface::NAME,
            CategoryInterface::ACTIVE
        ];

        /**
         * danh sách hiển thị các nút tính năng phần tử bảng.
         * áp dụng cho toàn bộ các phần tử.
         */
        $actionItems = [
            [
                'type' => 'edit',
                'url' => CategoryInterface::ROUTE_PREFIX . '/edit/',
                'label' => '',
                'icon' => 'edit',
            ],
            [
                'type' => 'delete',
                'url' => CategoryInterface::ROUTE_PREFIX . '/delete/',
                'label' => '',
                'icon' => 'delete',
            ],
        ];

        $lists = $this->categoryApi->paginate(6);

        return view('adminhtml.pages.categoryView.index', [
            'attributes' => $attributes,
            'lists' => $lists,
            'actions' => $actionItems
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Category::parentOptions();
        return view('adminhtml.pages.categoryView.create', [
            'listAttributes' => CategoryInterface::FORM_FIELDS
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id, Category $category)
    {
        $model = $category->find($id);
        return view('adminhtml.pages.categoryView.edit', [
            'method' => 'POST',
            'action' => url("adminhtml/category/update/" . $id),
            'listAttributes' => $model->formField(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateCategoryRequest $request, Category $category)
    // {
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
