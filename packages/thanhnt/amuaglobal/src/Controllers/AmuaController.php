<?php

namespace Thanhnt\Amuaglobal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Thanhnt\Amuaglobal\Helper\FileHelper;
use Illuminate\Support\Str;

class AmuaController extends Controller
{
	public function __construct(
		protected FileHelper $fileHelper
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * this request call artisan for clear cache of page
	 */
	public function clearCache(): string
	{
		/**
		 * call artisan command line in request process
		 * clear cache for app.
		 */
		$status = Artisan::call('cache:clear');
		return 'cache clear success!';
	}

	public function fileManage(Request $request)
	{
		return Inertia::render('Amuaglobal/Screens/Adminhtml/FileManageScreen',);
	}

	public function dirStructure(Request $request)
	{
		$path = $request->get('path') ?: 'galleries';
		/**
		 * doc: https://laravel.com/framework/docs/12.x/filesystem#main-content
		 * @var \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Filesystem\LocalFilesystemAdapter $fileSystem
		 */
		$fileSystem = Storage::disk('public');
		/**
		 * get files in directory galleries path
		 */
		$files = $fileSystem->files($path);
		/**
		 * get all dirrectories in directory path
		 * use: allFiles for get all file with loop deep in directory(lấy tất cả tập tin trong thu mục kể cả thư mục con)
		 */
		$directories = $fileSystem->allDirectories($path);

		return response()->json([
			'currentDirectory' => $path,
			'files' => array_map(function ($file) {
				return Storage::url($file);
			}, $files),
			'directories' => $directories,
		]);
	}

	/**
	 * upload files for file-manage dashboard
	 * @throws \Exception
	 */
	public function uploadFile(Request $request)
	{
		$savedFiles = [];
		foreach ($request->file('fileUpload') as $file) {
			try {
				$savedFiles[] = $this->fileHelper->uploadFile($file, $request->get('currentFolder', 'galleries'), ['disk' => 'public']);
			} catch (\Throwable $th) {
				continue;
			}
		}

		return response()->json([
			'status' => 'success',
			'uploaded' => $savedFiles,
		]);
	}

	public function addFolder(Request $request)
	{
		$request->validate([
			'name' => 'required',
		]);

		/**
		 * @var string $newFolder
		 * new folder name
		 */
		$newFolder = $request->get('name');
		/**
		 * @var \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Filesystem\LocalFilesystemAdapter $fileSystem
		 */
		$fileSystem = Storage::disk('public');
		/**
		 * create new folder
		 */
		$fileSystem->makeDirectory(($request->get('path') ?: 'galleries') . '/' . $newFolder);
		/**
		 * return response
		 * Lưu ý không truyền status:200 vào response(staus:200) sẽ gây ra lỗi phản hồi:
		 * message: "Method Illuminate\Http\Response::json does not exist.", exception: "BadMethodCallException",…}
		 * Vì khi truyền status:200 vào response thi Laravel sẽ trả về một đối tượng Illuminate\Http\Response
		 * thay vì ResponseFactory — và class Response này không có phương thức ->json().
		 * Thay vào đó cần truyền status:200 vào tham số thứ hai trong phương thức ->json().
		 */
		return response()->json([
			'message' => 'success',
			'folderName' => $newFolder,
			'folderUrl' => Storage::url($newFolder),
		], status: 200);
	}

	public function removeFolder(Request $request)
	{
		/**
		 * target folder path
		 * ex: galleries/product
		 */
		$path = $request->get('path');
		/**
		 * @var \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Filesystem\LocalFilesystemAdapter $fileSystem
		 */
		$fileSystem = Storage::disk('public');
		/**
		 * remove folder action
		 */
		$status = $fileSystem->deleteDirectory($path);
		/**
		 * return response
		 */
		return response()->json([
			'message' => $status ? 'success' : 'failed',
		], status: 200);
	}

	/**
	 * remove file support for file-manager
	 * @throws \Exception
	 */
	public function removeFile(Request $request)
	{
		$request->validate([
			'path' => 'required',
		]);
		/**
		 * lấy đường dẫn vị trí của file trong thư mục từ url path
		 * vì đường dẫn path truyền lên là 1 url tương đối với localhost:8000/storage/galleries/image3.png
		 * ex: /storage/galleries/image3.png -> galleries/image3.png
		 * @var string $relativePath
		 */
		$relativePath = Str::after($request->get('path'), '/storage/');

		/**
		 * @var \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Filesystem\LocalFilesystemAdapter $fileSystem
		 */
		$fileSystem = Storage::disk('public');
		/**
		 * delete file action
		 * /storage/galleries/image3.png
		 */
		$status = $fileSystem->delete($relativePath);

		return response()->json([
			'message' => $status ? 'success' : 'failed',
		], status: 200);
	}

	public function getImages(Request $request)
	{
		$path = $request->get('path', 'galleries');
		/**
		 * doc: https://laravel.com/framework/docs/12.x/filesystem#main-content
		 * @var \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Filesystem\LocalFilesystemAdapter $fileSystem
		 */
		$fileSystem = Storage::disk('public');
		/**
		 * get all files in directory galleries path
		 */
		$files = $fileSystem->allFiles($path);
		/**
		 * get all dirrectories in directory path
		 */
		$directories = $fileSystem->allDirectories($path);
		return Inertia::render('Amuaglobal/Screens/Adminhtml/ImageManage', [
			'currentDirectory' => $path,
			'files' => array_map(function ($file) {
				return Storage::url($file);
			}, $files),
			'directories' => $directories,
			// 'firstFileUrl' => !empty($files[0]) ? Storage::url($files[0]) : null
		]);
		dd($files, $directories, !empty($files[0]) ? Storage::url($files[0]) : null);
	}
}
