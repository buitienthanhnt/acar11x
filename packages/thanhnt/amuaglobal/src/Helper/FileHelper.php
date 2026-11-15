<?php

namespace Thanhnt\Amuaglobal\Helper;

use Illuminate\Http\UploadedFile;

final class FileHelper
{

	/**
	 * @param \Illuminate\Http\UploadedFile $uploadedFile
	 * @param string $directory
	 * @param array $options
	 * @return string|false
	 */
	public function uploadFile(UploadedFile $uploadedFile, $directory = '', $options = [])
	{
		/**
		 * doc: https://laravel.com/docs/8.x/filesystem#main-content
		 * @var \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Filesystem\LocalFilesystemAdapter $fileSystem
		 * 'public' value for disk name in config/filesystems.php
		 * @see config/filesystems.php
		 * Nếu hàm trả về false nghĩa là thư mục không có quyền ghi(cần cấp quyền):
		 * 	sudo chown -R www-data:www-data storage/
		 * 	sudo chmod -R 775 storage/
		 */
		return $uploadedFile->storeAs($directory, $uploadedFile->getClientOriginalName(), $options);
	}
}
