<div style="background-color: red; white-space: pre-wrap;">
    {{-- {!! nl2br(e($content)) !!} --}}
    {{ $content }}
</div>

{{-- Trong Laravel, nếu bạn muốn hiển thị nội dung file văn bản mà không muốn nó bị dồn dòng (mất định dạng xuống dòng), bạn có các cách xử lý sau đây:
1. Sử dụng thẻ <pre> trong Blade (Cách đơn giản nhất)
Thẻ HTML <pre> sẽ giữ nguyên mọi khoảng trắng, tab và dấu xuống dòng y hệt như trong file gốc.
html
<!-- Trong file .blade.php -->
<pre>{{ $content }}</pre>
Use code with caution.

2. Sử dụng hàm nl2br() kết hợp với e()
Nếu bạn muốn nội dung tự động xuống dòng theo kiểu HTML (<br>) nhưng vẫn đảm bảo an toàn (không bị lỗi XSS), hãy dùng tổ hợp này:
html
<!-- e() để bảo mật, nl2br() để chuyển dấu xuống dòng thành thẻ <br> -->
<div>
    {!! nl2br(e($content)) !!}
</div>
Use code with caution.

3. Trả về phản hồi dạng văn bản thuần (Plain Text)
Nếu bạn muốn trình duyệt hiển thị nội dung file như một trang văn bản thô (không có giao diện website), bạn cần thiết lập Content-Type là text/plain trong Controller:
php
use Illuminate\Support\Facades\Storage;

public function showFile()
{
    $content = Storage::get('your-file.txt');

    return response($content, 200)
        ->header('Content-Type', 'text/plain; charset=UTF-8');
}
Use code with caution.

4. Sử dụng CSS white-space
Nếu bạn muốn hiển thị trong một thẻ div hoặc span bình thường nhưng vẫn giữ định dạng dòng, hãy dùng CSS:
html
<div style="white-space: pre-wrap;">
    {{ $content }}
</div>
Use code with caution.

pre-wrap: Giữ nguyên xuống dòng và khoảng trắng, đồng thời tự động xuống dòng nếu văn bản quá dài vượt quá chiều rộng khung hình.
Lời khuyên: Nếu file của bạn là log hoặc mã nguồn, Cách 1 (thẻ <pre>) là lựa chọn phổ biến và dễ nhất. --}}
