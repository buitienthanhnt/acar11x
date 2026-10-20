<?php

namespace Thanhnt\Amuaglobal\Actions;

use Illuminate\Http\Request;
use Thanhnt\Amuaglobal\Helper\ModelHelper;
use Thanhnt\Amuaglobal\Models\Category;
use Thanhnt\Amuaglobal\Models\Types\CategoryInterface;

final class CategoryAction
{
	public function __construct(
		protected ModelHelper $modelHelper,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * action for register new category
	 * @param Request $request
	 * @return \Illuminate\Database\Eloquent\Model|null
	 * @throws \Exception
	 */
	public function register(Request $request)
	{
		$modelData = $request->toArray();
		/**
		 * save new category model
		 * @var Category $newCategory
		 */
		$newCategory = Category::create($this->modelHelper->massDataAttribute(CategoryInterface::FILLED_FIELDS, $modelData));
		/**
		 * update image for category after save new model 
		 * các thuộc tính update cần được khai báo trong $fillable attribute trong model 
		 */
		$newCategory->update([CategoryInterface::IMAGE_PATH => $this->upLoadImage($request)]);
		return $newCategory;
	}

	/**
	 * upload image for category
	 * @param Request $request
	 * @return string
	 */
	protected function upLoadImage(Request $request)
	{
		$image = $request->file(CategoryInterface::IMAGE_PATH);
		/**
		 * storeAs image for category vào thư mục categories
		 * sẽ vẫn giữ lại tên file ảnh đãtải lên
		 */
		return $image->storeAs('categories', $image->getClientOriginalName(), 'public');
		// throw new \Exception('Not implemented');
	}
}
