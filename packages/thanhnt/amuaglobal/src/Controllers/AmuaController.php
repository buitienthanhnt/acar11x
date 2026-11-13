<?php

namespace Thanhnt\Amuaglobal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AmuaController extends Controller
{
	public function __construct()
	{
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

	public function uploadFile(Request $request) {
		$files = $request->file('fileUpload');
		dd($files, $request->all());
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
