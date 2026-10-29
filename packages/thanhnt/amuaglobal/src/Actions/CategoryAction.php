<?php

namespace Thanhnt\Amuaglobal\Actions;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thanhnt\Amuaglobal\Helper\ModelHelper;
use Thanhnt\Amuaglobal\Models\Category;
use Thanhnt\Amuaglobal\Models\Types\CategoryInterface;

final class CategoryAction
{
	public function __construct(
		protected ModelHelper $modelHelper,
		protected Category $category,
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
		$newCategory->update([CategoryInterface::IMAGE => $this->upLoadImage($request->get(CategoryInterface::IMAGE))]);
		return $newCategory;
	}

	/**
	 * action for update category
	 * @param int $id
	 * @param array|Request $data
	 * @throws \Exception
	 */
	public function update(int $id, $data)
	{
		$category = $this->category->findOrFail($id);
		/**
		 * convert request to array
		 */
		if ($data instanceof \Illuminate\Http\Request) {
			$data = $data->toArray();
		}

		$category->update($this->modelHelper->massDataAttribute(CategoryInterface::FILLED_FIELDS, $data));

		/**
		 * update image for category
		 */
		if (!empty($data[CategoryInterface::IMAGE]) && $data[CategoryInterface::IMAGE] instanceof UploadedFile) {
			/**
			 * remove current image before upload new image
			 */
			$currenImage = $category->{CategoryInterface::IMAGE};
			Storage::delete($currenImage);
			/**
			 * update new image
			 */
			$category->update([CategoryInterface::IMAGE => $this->upLoadImage($data[CategoryInterface::IMAGE])]);
		} else if (empty($data[CategoryInterface::IMAGE])) {
			/**
			 * remove current image before upload new image
			 */
			$currenImage = $category->{CategoryInterface::IMAGE};
			Storage::delete($currenImage);
			$category->update([CategoryInterface::IMAGE => null]);
		}
		return $category;
	}

	/**
	 * upload image for category
	 * @param \Illuminate\Http\UploadedFile $image
	 * @return string
	 */
	protected function upLoadImage($image)
	{
		if (!$image instanceof UploadedFile) {
			return null;
		}
		/**
		 * storeAs image for category vào thư mục categories
		 * sẽ vẫn giữ lại tên file ảnh đãtải lên
		 */
		return $image->storeAs('categories', $image->getClientOriginalName(), 'public');
		// throw new \Exception('Not implemented');
	}
}
