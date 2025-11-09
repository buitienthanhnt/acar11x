<?php

namespace App\Helper;

use Illuminate\Support\Str;

trait ImageHelper
{

	/**
	 * upload singe file.
	 * @param \Illuminate\Http\UploadedFile $uploadFile
	 * @param string $dirPath (optional)
	 * @param string $fileName (optional)
	 * @return [storage_path => string,public_path => string,image_url => string]|null
	 */
	public function uploadImage($uploadFile, $dirPath = '', $fileName = '')
	{
		if (!$uploadFile) {
			return null;
		}
		$defaultDir = 'public/images/';
		$fileName = $fileName ?: $uploadFile->getClientOriginalName();

		/**
		 * hỗ trợ các định dạng file: jpg, jpeg, png
		 */
		$ext = $uploadFile->extension();
		if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
			return null;
		}

		/**
		 * thực hiện lưu hình ảnh vào trong storage
		 * trả về đường dẫn tương đối trong thư mục storage/app/publib/....
		 * tuy nhiên nó trả về chỉ tính từ public/dir/filename.(ext) 
		 * đây là đường dẫn tính theo thư mục và tên ảnh: public/images/writers/24/btaqwe.jpg
		 */
		$path = $uploadFile->storeAs(
			$dirPath ?: $defaultDir, // đường dẫn thư mục lưu ảnh theo storage/app->.
			($fileName ?: (Str::random(12)) . ".$ext")   // tên hình ảnh.
		);

		/**
		 * chuyển đổi đường dẫn tương ứng với thư mục root/public/storage/
		 * để truy cập được qua url.
		 */
		$usePath = 'storage/' . explode('public/', $path, 2)[1];

		/**
		 * đường dẫn url của hình ảnh.
		 */
		$imageUrl = asset($usePath);

		/**
		 * trả về 3 định dạng kết quả đường dẫn
		 * 1. đường dẫn tương đối trong thư mục storage(có thể dùng để xóa ảnh qua Storage)
		 * 2. đường dẫn tương đối trong thư mục public thông qua số 1(để lưu trong database và dùng qua asset($path))
		 * 3. đường dẫn tuyệt đối bằng url thông qua số 2(dùng trực tiếp không cần xử lý thêm).
		 */
		return [
			'storage_path' => $path,
			'public_path' => $usePath,
			'image_url' => $imageUrl
		];
	}

	/**
	 * upload multi files.
	 * @param \Illuminate\Http\UploadedFile[] $uploadFiles
	 * @return mixed|null
	 */
	public function UploadImages($uploadFiles = [], $dirPath = '')
	{
		$uploadedData = null;
		foreach ($uploadFiles as $uploadFile) {
			if ($uploadedItem = $this->uploadImage($uploadFile, dirPath: $dirPath)) {
				$uploadedData[] = $uploadedItem;
			}
		}
		return $uploadedData;
	}
}
