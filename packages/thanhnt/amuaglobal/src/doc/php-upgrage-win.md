 Để sử dụng 
# PHP 8.3 với XAMPP, bạn có hai lựa chọn chính: 
# tải bản cài đặt XAMPP mới nhất đã tích hợp sẵn PHP 8.3 
hoặc 
# nâng cấp thủ công phiên bản PHP trong bộ XAMPP hiện tại của bạn. 
1. Tải bản XAMPP mới nhất (Khuyên dùng) 
Cách đơn giản nhất là tải trực tiếp bộ cài đặt XAMPP đã bao gồm PHP 8.3 từ trang chủ Apache Friends. 
Truy cập trang Download XAMPP.
Chọn phiên bản tương ứng với hệ điều hành (Windows, Linux, hoặc OS X) có ghi chú PHP 8.3.x.
Tiến hành cài đặt như bình thường. Lưu ý nên sao lưu thư mục htdocs và cơ sở dữ liệu mysql/data nếu bạn đang dùng bản XAMPP cũ. 
GitHub
GitHub
 +4
2. Nâng cấp thủ công PHP 8.3 cho XAMPP hiện có 
Nếu bạn không muốn cài đặt lại toàn bộ XAMPP, bạn có thể thay thế thư mục PHP cũ bằng bản mới: 
GitHub
GitHub
 +1
# Tải PHP 8.3: Truy cập Windows PHP Downloads và tải bản VS16 x64 Thread Safe (dạng file Zip).
# Sao lưu: Đổi tên thư mục php hiện tại trong thư mục cài đặt XAMPP (thường là C:\xampp\php) thành php_old.
# Cài đặt bản mới: Tạo một thư mục php mới và giải nén các file từ bản PHP 8.3 vừa tải vào đây.
Cấu hình:
# Copy file php.ini-development hoặc php.ini-production trong thư mục mới và đổi tên thành php.ini.(Lưu ý là coppy sau đó đổi tên file mới là php.ini chứ không được đổi tên file ini-develop)
# Mở file php.ini để cấu hình lại các extension cần thiết (như extension_dir = "ext", curl, mysqli, v.v.).
Cập nhật file cấu hình Apache (httpd-xampp.conf) nếu đường dẫn hoặc tên file .dll của PHP thay đổi.
Khởi động lại: Restart Apache từ XAMPP Control Panel để áp dụng thay đổi. 
GitHub
GitHub
 +4
3. Chạy nhiều phiên bản PHP cùng lúc 
Nếu bạn cần chạy đồng thời cả PHP 8.3 và các bản cũ hơn (như 7.4 hay 8.2), bạn có thể cấu hình Apache listen trên các cổng khác nhau cho mỗi phiên bản hoặc sử dụng các công cụ như Docker để quản lý môi trường linh hoạt hơn. 
Stack Overflow
Stack Overflow
 +2
Bạn đang gặp khó khăn ở bước cài đặt mới hay nâng cấp cấu hình cho dự án hiện tại?





undefined
undefined
undefined
15 trang web
how to update xampp to be compatible with php 8.3.1? #103059
1 thg 2, 2024 — Duplicate the PHP folder, change the name of the copy to PHP_old, open the PHP folder. Download the new version of PHP, extract it...

GitHub

how to update xampp to be compatible with php 8.3.1? #155925
5 thg 4, 2025 — Option 1: Install Latest XAMPP with PHP 8.3. ... Download the latest XAMPP version with PHP 8.3. x: https://www.apachefriends.org/

GitHub

Is there way to use two PHP versions in XAMPP? - Stack Overflow
3.8(latest) : https://windows.php.net/download/

Stack Overflow

Hiện tất cả
# Eror: PHP Warning:  PHP Startup: Unable to load dynamic library 'curl' (tried: C:\php\ext\curl (The specified module could not be found), C:\php\ext\php_curl.dll (The specified module could not be found)) in Unknown on line 0
# Lỗi "Unable to load dynamic library 'curl'" thường xảy ra do PHP không tìm thấy các file thư viện bổ trợ (dependencies) cần thiết để chạy cURL trên Windows, dù file php_curl.dll đã tồn tại. 
Stack Overflow
Stack Overflow
 +1
Dưới đây là các bước để khắc phục triệt để lỗi này trên XAMPP PHP 8.3:
1. Thêm thư mục PHP vào biến môi trường PATH (Khuyên dùng) 
Đây là cách "chuẩn" nhất để Windows và Apache tìm thấy các file hỗ trợ của PHP. 
Stack Overflow
Stack Overflow
 +1
Nhấn phím Windows, gõ "env" và chọn Edit the system environment variables.
Nhấp vào nút Environment Variables.
Trong phần System variables, tìm dòng Path, chọn nó và nhấn Edit.
Nhấn New và thêm đường dẫn đầy đủ đến thư mục PHP của bạn (ví dụ: C:\xampp\php).
Quan trọng: Bạn phải khởi động lại máy tính (hoặc ít nhất là thoát hẳn và mở lại XAMPP Control Panel) để thay đổi có hiệu lực. 
Server Fault
Server Fault
 +1
2. Copy các file DLL vào thư mục Bin của Apache
Nếu cách trên không được, bạn có thể copy trực tiếp các file phụ thuộc vào nơi Apache đang chạy. 
Stack Overflow
Stack Overflow
 +1
Vào thư mục C:\xampp\php.
Tìm và copy các file sau:
libssh2.dll
libcrypto-3-x64.dll (Tên có thể khác một chút tùy bản build)
libssl-3-x64.dll
Dán (Paste) chúng vào thư mục C:\xampp\apache\bin.
Khởi động lại (Restart) Apache trong XAMPP Control Panel. 
3. Kiểm tra cấu hình trong php.ini
Hãy đảm bảo đường dẫn extension đang trỏ đúng file và sử dụng đường dẫn tuyệt đối nếu cần. 
Stack Overflow
Stack Overflow
 +1
Mở file C:\xampp\php\php.ini.
Đảm bảo dòng extension=curl đã được bỏ dấu chấm phẩy (;) ở đầu.
# Kiểm tra dòng extension_dir trong file php.ini. 
# Nếu đang để là ext, hãy thử đổi thành đường dẫn tuyệt đối(thông thường nên để là ext cần lưu ý phải bỏ dấu: ";" phía trước đi nếu không sẽ bị lỗi bên trên):
ini
# extension_dir = "C:\xampp\php\ext"
Hãy thận trọng khi sử dụng mã.

 
Stack Overflow
Stack Overflow
 +2
4. Cập nhật Apache (Dành cho bản PHP 8.3 mới)
Một số phiên bản PHP 8.3 yêu cầu thư viện nghttp2.dll hoặc các bản build mới hơn của Apache. Nếu bạn đang dùng một bản XAMPP quá cũ và chỉ nâng cấp lẻ PHP, hãy cân nhắc nâng cấp toàn bộ bản XAMPP mới nhất vì các file httpd.exe cũ có thể không tương thích với các module mới của PHP 8.3. 
WampServer
WampServer
 +1
Lưu ý: Bạn có thể kiểm tra xem cURL đã hoạt động chưa bằng cách tạo file info.php với nội dung <?php phpinfo(); ?> và tìm kiếm từ khóa "curl" trong trình duyệt. 
Stack Overflow
Stack Overflow
Bạn đã thử khởi động lại máy tính sau khi thêm biến môi trường PATH chưa? Đây thường là bước bị bỏ qua nhiều nhất khiến lỗi vẫn tồn tại.
