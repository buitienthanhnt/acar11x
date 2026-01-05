Skip to main contentAccessibility help
Accessibility feedback
AI Mode
All
Videos
Images
Shopping
More
Sign in


laravel stream large text file
To stream a large text file in Laravel for 2026, use methods that process data incrementally to avoid memory exhaustion. 
1. Streaming Downloads
For files already on disk, use streamDownload to serve them without loading the entire content into memory. 
Method: Storage::download() or response()->streamDownload().
Example:
php
return response()->streamDownload(function () {
    $file = fopen('path/to/large-file.txt', 'r');
    while (!feof($file)) {
        echo fread($file, 1024 * 8); // Stream in 8KB chunks
        flush(); // Force output to browser
    }
    fclose($file);
}, 'export.txt');
Use code with caution.

 
2. Reading Large Files Line-by-Line
To process or display a large local file, use a generator with fopen() to keep memory usage constant regardless of file size. 
Recommended approach:
php
function getLines($path) {
    $file = fopen($path, 'r');
    while (($line = fgets($file)) !== false) {
        yield $line;
    }
    fclose($file);
}

foreach (getLines('large-file.txt') as $line) {
    // Process each line
}
Use code with caution.

 
3. Streaming from Remote Sources
If the file is hosted elsewhere (e.g., an external API), use Laravel’s HTTP Client with the stream option to process the response body in chunks. 
Method: Http::withOptions(['stream' => true])->get($url).
Direct to Disk: Use the sink method to write a remote file directly to a path without buffering it in PHP memory.
php
Http::sink(storage_path('app/local-copy.txt'))
    ->get('https://example.com/huge-file.txt');
Use code with caution.

 
4. Handling Large Database Exports
When generating a text file (like CSV) from a database, use LazyCollection or chunk() to stream records. 
Example:
php
return response()->streamDownload(function () {
    $handle = fopen('php://output', 'w');
    User::query()->cursor()->each(function ($user) use ($handle) {
        fputcsv($handle, [$user->id, $user->email]);
    });
    fclose($handle);
}, 'users.csv');
Use code with caution.

 
Performance Tips
Time Limits: For very large files, ensure you call set_time_limit(0) to prevent the script from timing out.
S3 Integration: If using AWS S3, use Storage::readStream() and Storage::writeStream() to pipe data directly between streams.
Memory Management: Avoid using file_get_contents() or Eloquent's all() on large datasets, as these load everything into RAM at once. 
AI can make mistakes, so double-check responses




undefined
undefined
undefined
22 sites
2015-02-08-streaming-large-csv-files-with-laravel-chunk.md
Feb 6, 2015 — Chunking large queries. But what if you have thousands of rows in your database? The Streaming would work, but you can ...

GitHub
Streaming large file downloads in Laravel. - DEV Community
Dec 12, 2019 — In my role as a developer at Reviews.io we often have customers who want to export large datasets from our Dashboard. ...

DEV Community

Using Response::download for large files - Laracasts
Nov 1, 2019 — Using Response::download for large files Is it good to use Response::download for large files? Because i googled it bef...

Laracasts
Show all
stream to show
To stream a large text file so its content appears directly in the browser (e.g., for viewing logs or long reports) without downloading it as an attachment, use Laravel's response()->stream() method. 
1. Basic Streaming to Browser
To show content in the browser, you must omit the Content-Disposition header or set it to inline. This prevents the browser from triggering a download dialog. 
php
use Illuminate\Support\Facades\Storage;

Route::get('/view-log', function () {
    return response()->stream(function () {
        $stream = fopen(storage_path('app/large-file.txt'), 'r');
        while (!feof($stream)) {
            echo fread($stream, 8192); // Send 8KB at a time
            flush(); // Push the buffer to the browser immediately
        }
        fclose($stream);
    }, 200, [
        'Content-Type' => 'text/plain',
        'Content-Disposition' => 'inline; filename="large-file.txt"',
    ]);
});
Use code with caution.

2. Livewire Streaming (Modern Approach)
In 2026, Livewire 3 provides a dedicated stream() method that is ideal for progressively displaying text (like AI chat responses or live logs) within a component. 
In your Component:
php
public function showContent()
{
    $file = fopen(storage_path('app/large-file.txt'), 'r');
    while (($line = fgets($file)) !== false) {
        $this->stream(to: 'file-content', content: $line, replace: false);
        usleep(50000); // Optional: delay for visual effect
    }
    fclose($file);
}
Use code with caution.

In your View:
html
<div wire:stream="file-content">
    <!-- Content will appear here incrementally -->
</div>
Use code with caution.

3. Server-Sent Events (SSE)
For a more robust "live" experience where the browser remains responsive while receiving data, use Server-Sent Events. 
Best for: Real-time log monitoring or processing progress.
Frontend: Use the native JavaScript EventSource API to listen to your Laravel stream. 
Key Considerations for 2026
Output Buffering: Ensure output_buffering is disabled in your php.ini or handled via ob_end_flush() to prevent PHP from waiting until the end of the script to send data.
Time Limits: Large files may exceed default execution times; use set_time_limit(0) to allow the stream to finish.
Memory: Always use fopen() and fread()/fgets() rather than file_get_contents() to keep memory usage low regardless of file size. 




undefined
undefined
undefined
9 sites
Streaming a large file using PHP - Stack Overflow
Aug 1, 2011 — <? php // the file you want to send $path = "path/to/file"; // the file name of the download, change this if needed $pu...

Stack Overflow

Optimizing Large Data Delivery with Laravel Streaming ...
Jan 6, 2025 — Optimizing Large Data Delivery with Laravel Streaming Responses. Published on January 7th, 2025 by Harris Raftopoulos. ...

Laravel News

Streaming Data from Laravel to the Browser - Patrick Stephan
Feb 5, 2025 — WebSockets are a bit overkill for this particular feature. If I already had webSockets set up, I might use it, but sinc...

patrickstephan.me

Show all
toi muon stream noi dung 1 fiile text bang laravel
Để stream nội dung một file văn bản lớn trong Laravel (phiên bản năm 2026) mà không làm tràn bộ nhớ (RAM), bạn nên sử dụng StreamedResponse. Cách tiếp cận này giúp đọc file theo từng phân đoạn (chunks) và gửi trực tiếp đến trình duyệt.
Dưới đây là 3 cách phổ biến nhất:
1. Cách cơ bản: Hiển thị trực tiếp nội dung ra trình duyệt
Cách này phù hợp để hiển thị các file log hoặc nội dung văn bản dài ngay trên tab trình duyệt.
php
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

Route::get('/stream-text', function () {
    return new StreamedResponse(function () {
        // Mở file ở chế độ đọc (read-only)
        $handle = fopen(storage_path('app/large-file.txt'), 'r');

        while (!feof($handle)) {
            // Đọc và in ra 8KB mỗi lần
            echo fread($handle, 8192);
            
            // Đẩy nội dung ra trình duyệt ngay lập tức
            if (ob_get_level() > 0) ob_flush();
            flush();
        }
        fclose($handle);
    }, 200, [
        'Content-Type' => 'text/plain; charset=UTF-8',
        'Cache-Control' => 'no-cache',
        'X-Accel-Buffering' => 'no', // Quan trọng nếu dùng Nginx
    ]);
});
Use code with caution.

2. Sử dụng response()->stream() (Cú pháp ngắn gọn)
Laravel cung cấp hàm helper tiện lợi hơn để thực hiện cùng một công việc:
php
return response()->stream(function () {
    $path = storage_path('app/data.txt');
    $file = fopen($path, 'r');

    while (($line = fgets($file)) !== false) {
        echo $line;
        flush();
    }
    fclose($file);
}, 200, ['Content-Type' => 'text/plain']);
Use code with caution.

3. Stream từ Cloud Storage (S3 / Google Cloud)
Nếu file của bạn nằm trên AWS S3, bạn không nên tải toàn bộ file về máy chủ. Hãy dùng readStream:
php
use Illuminate\Support\Facades\Storage;

Route::get('/stream-s3', function () {
    return response()->stream(function () {
        $stream = Storage::disk('s3')->readStream('large-document.txt');

        while (!feof($stream)) {
            echo fread($stream, 8192);
            flush();
        }
        fclose($stream);
    }, 200, ['Content-Type' => 'text/plain']);
});
Use code with caution.

Các lưu ý quan trọng cho năm 2026:
X-Accel-Buffering: Nếu bạn dùng Nginx, bạn phải đặt header 'X-Accel-Buffering' => 'no' để Nginx không đợi nhận đủ file mới gửi cho người dùng.
Giới hạn thời gian: Với file cực lớn, hãy thêm set_time_limit(0); ở đầu hàm để PHP không ngắt kết nối giữa chừng.
Trải nghiệm người dùng: Nếu bạn muốn nội dung hiện lên từ từ giống như ChatGPT (hiệu ứng gõ chữ), bạn nên kết hợp với Livewire 3 Stream hoặc Server-Sent Events (SSE).
Bộ nhớ: Tuyệt đối tránh dùng file_get_contents() hoặc Storage::get() vì các hàm này sẽ tải toàn bộ dung lượng file vào RAM, dễ gây lỗi Out of Memory.



stream truc tiep tren trinh duyet
Để stream trực tiếp nội dung một file văn bản lên trình duyệt (người dùng thấy chữ chạy đến đâu hiện đến đó, không phải tải về), bạn cần cấu hình Header chính xác để trình duyệt hiểu đây là dữ liệu hiển thị (inline) thay vì file tải về.
Dưới đây là cách thực hiện tối ưu nhất trong Laravel năm 2026:
1. Sử dụng response()->stream()
Đây là cách tiêu chuẩn để đẩy dữ liệu từng phần từ server xuống trình duyệt.
php
use Illuminate\Support\Facades\File;

Route::get('/view-stream', function () {
    $filePath = storage_path('app/logs/large-log.txt');

    if (!File::exists($filePath)) {
        abort(404);
    }

    return response()->stream(function () use ($filePath) {
        $file = fopen($filePath, 'r');
        
        // Đọc từng dòng hoặc từng phân đoạn 8KB
        while (!feof($file)) {
            $buffer = fread($file, 8192);
            echo $buffer;
            
            // Xóa bộ đệm PHP và đẩy dữ liệu xuống trình duyệt ngay lập tức
            if (ob_get_level() > 0) ob_flush();
            flush();
            
            // Tùy chọn: Thêm độ trễ nhỏ nếu muốn thấy hiệu ứng chữ chạy từ từ
            // usleep(50000); 
        }
        fclose($file);
    }, 200, [
        'Content-Type' => 'text/plain; charset=UTF-8',
        'Content-Disposition' => 'inline', // Quan trọng: Hiển thị trực tiếp, không tải về
        'Cache-Control' => 'no-cache',
        'X-Accel-Buffering' => 'no', // Cần thiết cho Nginx để tắt buffering
    ]);
});
Use code with caution.

2. Những lưu ý kỹ thuật bắt buộc
Header X-Accel-Buffering: no: Nếu server của bạn chạy Nginx, Nginx sẽ cố gắng đợi nhận đủ một lượng dữ liệu lớn rồi mới gửi một lần. Header này bắt Nginx phải gửi dữ liệu ngay khi nó nhận được từ PHP.
Tắt Output Buffering: Đảm bảo file cấu hình php.ini không bật output_buffering quá lớn, hoặc sử dụng ob_flush() và flush() như ví dụ trên để dọn dẹp bộ đệm thủ công.
Giới hạn thời gian (Timeout): Với các file cực lớn mất nhiều phút để stream, hãy thêm lệnh này ở đầu Controller:
php
set_time_limit(0); // Không giới hạn thời gian thực thi script
Use code with caution.

3. Stream nội dung từ Database (ví dụ: Xuất dữ liệu)
Nếu bạn không stream file có sẵn mà stream dữ liệu từ Database ra định dạng văn bản:
php
return response()->stream(function () {
    $handle = fopen('php://output', 'w');
    
    // Sử dụng cursor() để tiết kiệm RAM, không tải toàn bộ DB vào bộ nhớ
    User::query()->cursor()->each(function ($user) use ($handle) {
        fwrite($handle, "ID: {$user->id} - Email: {$user->email}" . PHP_EOL);
        if (ob_get_level() > 0) ob_flush();
        flush();
    });
    
    fclose($handle);
}, 200, ['Content-Type' => 'text/plain']);
Use code with caution.

4. Cách kiểm tra
Truy cập vào URL bằng trình duyệt (Chrome/Edge/Firefox).
Nếu file rất lớn, bạn sẽ thấy thanh cuộn của trình duyệt tự động dài ra và nội dung mới xuất hiện liên tục mà trang web không cần tải lại (F5).
Mẹo: Nếu bạn muốn stream theo kiểu AI (từng chữ một) cho giao diện hiện đại, bạn nên cân nhắc sử dụng Livewire 3 Streaming để tích hợp mượt mà vào giao diện HTML.



Storage::readSream() thi sao
Sử dụng Storage::readStream() là cách chuyên nghiệp và tối ưu nhất trong Laravel khi bạn làm việc với Filesystem (đặc biệt là khi file nằm trên S3, Google Cloud Storage hoặc FTP).
Thay vì đọc thủ công bằng fopen, readStream trả về một "PHP stream resource". Kết hợp nó với fpassthru sẽ giúp bạn đẩy toàn bộ nội dung file ra trình duyệt với mức tiêu thụ RAM gần như bằng 0.
1. Cách sử dụng cơ bản (Stream trực tiếp)
Đây là cách ngắn gọn nhất để hiển thị nội dung file từ Storage lên trình duyệt:
php
use Illuminate\Support\Facades\Storage;

Route::get('/stream-view', function () {
    $path = 'large-file.txt';

    if (!Storage::exists($path)) abort(404);

    return response()->stream(function () use ($path) {
        // Lấy stream resource từ Storage
        $stream = Storage::readStream($path);

        // Đẩy thẳng stream này ra output buffer
        fpassthru($stream);

        // Đóng stream sau khi xong
        if (is_resource($stream)) {
            fclose($stream);
        }
    }, 200, [
        'Content-Type' => 'text/plain; charset=UTF-8',
        'Content-Disposition' => 'inline',
        'X-Accel-Buffering' => 'no',
    ]);
});
Use code with caution.

2. Tại sao nên dùng readStream() thay vì fopen()?
Tính trừu tượng (Abstraction): Bạn không cần quan tâm file đang ở đâu (ổ cứng local, Amazon S3, hay Azure). Storage::readStream() tự động xử lý việc kết nối và lấy stream cho bạn.
Tiết kiệm RAM tuyệt đối: fpassthru() sẽ đọc từ stream và đẩy thẳng vào buffer đầu ra của hệ thống, không bao giờ nạp nội dung file vào biến PHP.
An toàn: Laravel quản lý các cấu hình bảo mật và quyền truy cập file thông qua Cloud Disk cho bạn.
3. Stream từ S3 (Cloud) lên trình duyệt
Nếu bạn để file trên S3, việc dùng Storage::get() sẽ khiến server của bạn phải tải toàn bộ file từ S3 về RAM rồi mới gửi cho khách. Dùng readStream sẽ tạo một đường ống (pipeline) dẫn trực tiếp từ S3 -> Server của bạn -> Trình duyệt:
php
return response()->stream(function () {
    // Laravel tự kết nối S3 và mở stream
    $stream = Storage::disk('s3')->readStream('video/big-data.log');
    
    fpassthru($stream);
    
    if (is_resource($stream)) fclose($stream);
}, 200, ['Content-Type' => 'text/plain']);
Use code with caution.

4. So sánh nhanh
Phương pháp	Ưu điểm	Nhược điểm
Storage::get()	Dễ viết	Nguy hiểm: Gây lỗi sập server (Out of Memory) nếu file > dung lượng RAM cho phép.
fopen()	Kiểm soát thủ công tốt	Phải xử lý đường dẫn vật lý phức tạp, khó dùng với Cloud (S3).
Storage::readStream()	Tốt nhất: Chạy mượt với cả Local & Cloud, cực kỳ tiết kiệm RAM.	Cần hiểu về PHP Resource.
Lưu ý nhỏ cho năm 2026:
Khi dùng readStream(), nếu file của bạn cực lớn (vài GB), trình duyệt có thể ngắt kết nối nếu không thấy dữ liệu phản hồi trong một khoảng thời gian. Hãy đảm bảo header Content-Length được gửi nếu bạn biết trước dung lượng file:
php
'Content-Length' => Storage::size($path),
Use code with caution.

Điều này giúp trình duyệt hiển thị thanh tiến trình (progress bar) chính xác hơn.



Livewire 3 Stream nhu nao
Trong Livewire 3 (phiên bản năm 2026), tính năng Streaming cho phép bạn gửi dữ liệu từ máy chủ đến trình duyệt theo từng phần trước khi yêu cầu (request) hoàn thành. Điều này cực kỳ hữu ích để hiển thị nội dung file văn bản lớn hoặc phản hồi từ AI (như ChatGPT) một cách tức thì. 
1. Cách hoạt động cơ bản
Bạn sử dụng phương thức $this->stream() trong Class và directive wire:stream trong View. 
Trong Livewire Component Class:
php
namespace App\Livewire;

use Livewire\Component;

class FileStreamer extends Component
{
    public function streamFile()
    {
        $path = storage_path('app/large-text.txt');
        $handle = fopen($path, 'r');

        while (($line = fgets($handle)) !== false) {
            // Gửi từng dòng văn bản xuống trình duyệt
            $this->stream(
                to: 'file-content', // ID của vùng nhận dữ liệu
                content: $line, 
                replace: false      // false để nối thêm nội dung, true để thay thế
            );
            
            // Tùy chọn: Nghỉ một chút để thấy hiệu ứng stream
            usleep(20000); 
        }

        fclose($handle);
    }

    public function render()
    {
        return view('livewire.file-streamer');
    }
}
Use code with caution.

Trong Blade View (livewire/file-streamer.blade.php):
Sử dụng attribute wire:stream để đánh dấu nơi dữ liệu sẽ được đẩy vào. 
html
<div>
    <button wire:click="streamFile">Bắt đầu đọc file</button>

    <div style="white-space: pre-wrap; border: 1px solid #ccc; padding: 10px;">
        <!-- Dữ liệu sẽ xuất hiện dần dần ở đây -->
        <span wire:stream="file-content">Đang chờ dữ liệu...</span>
    </div>
</div>
Use code with caution.

2. Các tham số của phương thức stream()
to: Tên định danh (ID) phải khớp với giá trị trong wire:stream ở giao diện.
content: Nội dung văn bản bạn muốn gửi đi.
replace:
false (mặc định): Nối thêm nội dung mới vào sau nội dung cũ (phù hợp đọc log, chat AI).
true: Thay thế hoàn toàn nội dung cũ bằng nội dung mới (phù hợp làm đồng hồ đếm ngược). 
3. Lưu ý quan trọng
Không tương thích với Laravel Octane: Hiện tại Livewire 3 Stream chưa hỗ trợ nếu bạn đang chạy ứng dụng trên môi trường Octane.
Cấu hình Server: Đảm bảo máy chủ (Nginx/Apache) không bật buffering quá mức. Với Nginx, bạn nên có header X-Accel-Buffering: no.
Trải nghiệm người dùng: Streaming giúp trang web cảm giác nhanh hơn vì người dùng thấy dữ liệu ngay lập tức thay vì phải đợi server xử lý xong toàn bộ file lớn rồi mới phản hồi. 
Video sau đây giải thích về các phương thức vòng đời (lifecycle methods) trong Livewire 3, giúp bạn hiểu rõ thời điểm dữ liệu được xử lý và render:
Related video thumbnail
1m
Livewire 3 Lifecycle Methods Explained with Practical Examples


Programming Fields
YouTube • Jul 4, 2024




undefined
undefined
undefined
4 sites
wire:stream - Laravel Livewire
Are you a visual learner? Livewire allows you to stream content to a web page before a request is complete via the wire:stream API...

Laravel Livewire
livewire:stream #7047 - GitHub
Dec 24, 2024 — use Livewire\Component; class CountDown extends Component. { public $start = 3; public function begin() { while ($star...

GitHub

Checkboxes, and Streamed updates with Livewire · Laravel Bytes
Oct 25, 2023 — What We'll Do. To showcase Livewire's wire:stream directive, today, we'll make use of a table displaying rows of “prop...

Fly.io

Show all
co the lam tuong tu voi inertiajs khong
Inertia.js không hỗ trợ streaming dữ liệu trực tiếp vào component giống như cách wire:stream của Livewire 3 thực hiện. Lý do là Inertia hoạt động dựa trên giao thức JSON (gửi yêu cầu XHR/Fetch và nhận về một đối tượng JSON đầy đủ để cập nhật trạng thái của trang).
Tuy nhiên, vào năm 2026, bạn có 2 cách phổ biến để đạt được trải nghiệm tương tự trong một ứng dụng sử dụng Inertia.js:
1. Sử dụng Server-Sent Events (SSE) - Cách tốt nhất
Đây là cách chuẩn nhất để stream dữ liệu từ Laravel xuống một giao diện Vue hoặc React trong Inertia.
Bước 1: Tạo Route để stream (không qua Inertia)
Bạn tạo một Controller bình thường trả về StreamedResponse.
php
// Routes: routes/web.php
Route::get('/api/stream-log', [LogController::class, 'stream']);

// Controller: LogController.php
public function stream() {
    return response()->stream(function () {
        $handle = fopen(storage_path('app/large-file.txt'), 'r');
        while (($line = fgets($handle)) !== false) {
            // Định dạng SSE: bắt đầu bằng "data: " và kết thúc bằng "\n\n"
            echo "data: " . $line . "\n\n";
            ob_flush();
            flush();
            usleep(50000); 
        }
        fclose($handle);
    }, 200, [
        'Content-Type' => 'text/event-stream',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
    ]);
}
Use code with caution.

Bước 2: Tiếp nhận dữ liệu trong Component (Vue/React)
Trong file .vue hoặc .jsx của Inertia, bạn dùng API EventSource của trình duyệt.
javascript
// Ví dụ trong Vue 3
import { ref, onUnmounted } from 'vue';

const content = ref('');

const startStreaming = () => {
    const eventSource = new EventSource('/api/stream-log');

    eventSource.onmessage = (event) => {
        content.value += event.data + '\n';
    };

    eventSource.onerror = () => {
        eventSource.close();
    };

    // Đóng kết nối khi component bị hủy
    onUnmounted(() => eventSource.close());
};
Use code with caution.

2. Sử dụng ReadableStream (Fetch API)
Bạn có thể sử dụng fetch để đọc dữ liệu thô từ một endpoint stream.
javascript
const response = await fetch('/api/stream-text');
const reader = response.body.getReader();
const decoder = new TextDecoder();

while (true) {
    const { done, value } = await reader.read();
    if (done) break;
    
    const chunk = decoder.decode(value, { stream: true });
    this.textContents += chunk; // Cập nhật state để hiển thị lên giao diện
}
Use code with caution.

3. Tại sao không dùng Inertia "chính chủ"?
Inertia được thiết kế để đồng bộ hóa trạng thái (state) giữa PHP và JavaScript. Khi bạn stream, trạng thái bị thay đổi liên tục và dở dang, điều này đi ngược lại nguyên lý "giao dịch hoàn tất" của Inertia.
Nếu bạn cố tình gửi stream qua Inertia bằng return response()->stream(...), trình duyệt sẽ hiểu đó là một tải tập tin hoặc lỗi thay vì cập nhật Props của component.
Tóm lại: Với Inertia, bạn hãy dùng Inertia để render khung trang, sau đó dùng SSE hoặc Fetch API để đổ dữ liệu stream vào các vùng nội dung cụ thể.



react dung qua axios nhu nao
Thinking
Trong thực tế, Axios không phải là công cụ tốt nhất để stream dữ liệu vì nó được thiết kế để đợi cho đến khi nhận được toàn bộ phản hồi mới giải quyết (resolve) Promise.
Tuy nhiên, bạn vẫn có thể làm được bằng cách sử dụng callback onDownloadProgress của Axios, hoặc tốt hơn là dùng Fetch API (được khuyến khích hơn cho việc stream vào năm 2026).
Dưới đây là cả hai cách để bạn so sánh:
Cách 1: Dùng Axios (Sử dụng onDownloadProgress)
Cách này hoạt động bằng cách đọc biến event.currentTarget.response mỗi khi có dữ liệu mới tải xuống.
jsx
import React, { useState } from 'react';
import axios from 'axios';

const LogStreamer = () => {
    const [content, setContent] = useState('');

    const startStreaming = async () => {
        try {
            await axios.get('/api/stream-text', {
                responseType: 'text',
                onDownloadProgress: (progressEvent) => {
                    // Axios cung cấp toàn bộ nội dung đã nhận được tính đến thời điểm đó
                    const rawContent = progressEvent.currentTarget.response;
                    setContent(rawContent);
                },
            });
        } catch (error) {
            console.error("Stream lỗi:", error);
        }
    };

    return (
        <div>
            <button onClick={startStreaming}>Xem Log qua Axios</button>
            <pre style={{ whiteSpace: 'pre-wrap', background: '#f4f4f4' }}>{content}</pre>
        </div>
    );
};
Use code with caution.

Nhược điểm: Biến content sẽ bị ghi đè toàn bộ liên tục, và với file cực lớn, chuỗi văn bản trong bộ nhớ sẽ ngày càng nặng.
Cách 2: Dùng Fetch API (Khuyên dùng cho Stream thực thụ)
Fetch API hỗ trợ ReadableStream, cho phép bạn xử lý từng "chunk" (mảnh) dữ liệu ngay khi nó vừa rời khỏi server, giúp tiết kiệm RAM vượt trội.
jsx
import React, { useState } from 'react';

const FetchStreamer = () => {
    const [content, setContent] = useState('');

    const startStreaming = async () => {
        const response = await fetch('/api/stream-text');
        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        
        while (true) {
            const { value, done } = await reader.read();
            if (done) break;

            // Giải mã mảnh dữ liệu vừa nhận được
            const chunk = decoder.decode(value, { stream: true });
            
            // Cập nhật thêm vào state hiện tại
            setContent((prev) => prev + chunk);
        }
    };

    return (
        <div>
            <button onClick={startStreaming}>Stream bằng Fetch (Tối ưu)</button>
            <pre>{content}</pre>
        </div>
    );
};
Use code with caution.

Tại sao nên dùng Fetch thay vì Axios cho Stream?
Hiệu năng: Fetch xử lý dữ liệu theo luồng thực thụ. Nó không cần giữ toàn bộ file trong bộ nhớ đệm của thư viện như Axios.
Độ trễ: Với Fetch, bạn có thể xử lý dữ liệu ngay mili giây đầu tiên nó đến. Axios onDownloadProgress đôi khi bị trễ tùy thuộc vào trình duyệt và phiên bản thư viện.
Hỗ trợ mặc định: Năm 2026, tất cả trình duyệt hiện đại đều tối ưu hóa ReadableStream cho Fetch.
Lưu ý phía Laravel (Server)
Dù bạn dùng React hay Vue, phía Laravel bắt buộc phải có các header này để trình duyệt không đợi:
php
return response()->stream(function () {
    // ... code đọc file
}, 200, [
    'Content-Type' => 'text/plain',
    'X-Accel-Buffering' => 'no', // Tắt bộ đệm Nginx
	
    'Cache-Control' => 'no-cache',
]);
Use code with caution.




Ask anything


AI Mode response is ready