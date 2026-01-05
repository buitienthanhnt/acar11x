<?php

namespace Thanhnt\Ahomeglobal\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

final class AhomeTestController extends Controller
{
	/**
	 * Lưu ý là các phương thức stream khi hiển thị trên browser sẽ vẫn giữ nguyên được định dạng mà không bị nén
	 * stream large file
	 * live for inertiaJs component
	 * @return \Symfony\Component\HttpFoundation\StreamedResponse
	 */
	public function stream()
	{
		$filePath = 'private/large-file.txt';

		return response()->stream(function () use ($filePath) {
			$handle = fopen(storage_path('app/' . $filePath), 'r');
			/**
			 * đọc từng dòng và trả về nội dung.
			 */
			while (($line = fgets($handle)) !== false) {
				// Định dạng SSE: bắt đầu bằng "data: " và kết thúc bằng "\n\n"
				echo "data: " . $line . "\n\n";
				ob_flush();
				flush();
				usleep(1000); // tạo độ trễ nhỏ nếu muốn thấy hiệu ứng chữ chạy từ từ 1000 = 1ms: https://www.php.net/manual/en/function.usleep.php
			}
			fclose($handle);
		}, 200, [
			'Content-Type' => 'text/event-stream',
			'Cache-Control' => 'no-cache',
			'Connection' => 'keep-alive',
			'X-Accel-Buffering' => 'no', // Cần thiết cho Nginx để tắt buffering
		]);
	}

	/**
	 * // c1
	 * phương pháp stream thông thường
	 * đọc từng dòng hoặc từng phân đoạn 8KB
	 * rồi hiển thị ra trình duyệt
	 * @return \Symfony\Component\HttpFoundation\StreamedResponse
	 */
	function baseStream()
	{
		return response()->stream(function () {
			$stream = fopen(storage_path('app/private/large-file.txt'), 'r');
			while (!feof($stream)) {
				echo fread($stream, 8192); // Send 8KB at a time
				flush(); // Push the buffer to the browser immediately
			}
			fclose($stream);
		}, 200, [
			'Content-Type' => 'text/plain',
			'Content-Disposition' => 'inline; filename="large-file.txt"',
			'X-Accel-Buffering' => 'no', // cần thiết
		]);
	}

	/**
	 * c2( Sử dụng Server-Sent Events (SSE) - Cách tốt nhất) stream 1.76s hay hon c1
	 */
	public function hightStream()
	{
		$filePath = 'private/large-file.txt';
		if (!Storage::exists($filePath)) abort(404);

		return response()->stream(function () {
			$_filePath = storage_path('app/private/large-file.txt');
			$file = fopen($_filePath, 'r');

			while (!feof($file)) {
				/**
				 * Đọc từng dòng hoặc từng phân đoạn 8KB
				 */
				$buffer = fread($file, 8192);
				echo $buffer;
				// Xóa bộ đệm PHP và đẩy dữ liệu xuống trình duyệt ngay lập tức
				if (ob_get_level() > 0) ob_flush();
				flush();

				// Tùy chọn: Thêm độ trễ nhỏ nếu muốn thấy hiệu ứng chữ chạy từ từ
				usleep(50000);
			}
			fclose($file);
		}, 200, [
			'Content-Type' => 'text/plain; charset=UTF-8',
			'Content-Disposition' => 'inline', // Quan trọng: Hiển thị trực tiếp, không tải về
			'Cache-Control' => 'no-cache',
			'X-Accel-Buffering' => 'no', // Cần thiết cho Nginx để tắt buffering
			'Content-Length' => Storage::size($filePath),
		]);
	}

	/**
	 * c3: stream 150 ms(nhanh nhat)
	 * Dùng Storage để đọc file lớn, phù hợp cho cả s3
	 * @return \Symfony\Component\HttpFoundation\StreamedResponse
	 */
	public function optimateStream()
	{
		$filePath = 'private/large-file.txt';
		if (!Storage::exists($filePath)) abort(404);
		return response()->stream(function () use ($filePath) {
			/**
			 * tạo stream
			 */
			$stream = Storage::readStream($filePath);
			// Đẩy thẳng stream này ra output buffer
			fpassthru($stream);
			// Đóng stream sau khi xong
			if (is_resource($stream)) {
				fclose($stream);
			}
		}, 200, [
			'Content-Type' => 'text/plain; charset=UTF-8',
			'Content-Disposition' => 'inline', // Quan trọng: Hiển thị trực tiếp, không tải về
			'Cache-Control' => 'no-cache',
			'X-Accel-Buffering' => 'no', // Cần thiết cho Nginx để tắt buffering
			'Content-Length' => Storage::size($filePath),
		]);
	}

	/**
	 * hiển thị text content trên view truc tiep khong phai stream
	 */
	function viewTextContent()
	{
		$filePath = 'private/large-file.txt';
		/**
		 * c1: hiển thị thông thường
		 * get full content then reuturn to browser show
		 */
		$content = file_get_contents(storage_path('app/' . $filePath));
		return response($content, 200)
			->header('Content-Type', 'text/plain; charset=UTF-8'); // để giữ nguyên dạng file mà không bị nén


		// c2: sử dụng Storage để get content
		$content = Storage::get($filePath);
		return view('pages.text-content', compact('content'));
	}
}
