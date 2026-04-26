# browser print to pdf


Lưu một trang web dưới dạng PDF từ trình duyệt rất đơn giản vì hầu hết các trình duyệt hiện nay đều tích hợp sẵn tính năng này thông qua lệnh In (Print). 
Adobe
Adobe
 +1
1. Cách thực hiện trên máy tính (Windows & Mac)
Tất cả các trình duyệt phổ biến như Chrome, Edge, Firefox và Safari đều sử dụng quy trình tương tự: 
Mở menu In: Nhấn phím tắt Ctrl + P (Windows) hoặc Cmd + P (Mac).
Chọn Đích đến (Destination): Trong danh sách máy in, hãy chọn "Lưu thành PDF" (Save as PDF) hoặc "Microsoft Print to PDF".
Tùy chỉnh (Nếu cần): Click vào "Cài đặt khác" (More settings) để điều chỉnh khổ giấy, tỷ lệ (Scale) hoặc bật "Đồ họa nền" (Background graphics) để giữ lại màu sắc và hình ảnh của trang web.
Lưu: Nhấn nút Lưu (Save) hoặc In (Print), sau đó chọn thư mục lưu trữ trên máy tính. 
Adobe
Adobe
 +3
2. Cách thực hiện trên thiết bị di động
Android (Chrome): Chạm vào menu ba chấm → Chia sẻ (Share) → In (Print). Chọn "Lưu thành PDF" ở menu thả xuống phía trên cùng.
iPhone/iPad (Safari): Chạm vào nút Chia sẻ (Share) → In (Print). Ở màn hình xem trước, dùng hai ngón tay "phóng to" (pinch out) trang web để chuyển nó thành bản xem trước PDF toàn màn hình, sau đó chọn lại nút Chia sẻ để lưu vào tệp. 
Smallpdf
Smallpdf
 +3
3. Mẹo để có bản PDF đẹp hơn
Chế độ Đọc (Reader Mode): Nếu trang web có quá nhiều quảng cáo, hãy chuyển sang Reader Mode (trên Firefox hoặc Safari) trước khi nhấn In để có bản PDF sạch sẽ, chỉ có văn bản và hình ảnh chính.
Khắc phục lỗi mất hình ảnh: Đảm bảo bạn đã tích chọn "Background graphics" trong phần cài đặt in.
Sử dụng Tiện ích mở rộng (Extensions): Nếu cần lưu toàn bộ trang web dài (scrolling page) mà trình duyệt mặc định bị lỗi, bạn có thể dùng các công cụ như PrintFriendly để chỉnh sửa nội dung trước khi lưu. 
Chrome Web Store
Chrome Web Store
 +2
Bạn đang gặp vấn đề cụ thể nào khi lưu PDF (ví dụ: mất định dạng hay không tìm thấy nút lưu) không?


10 trang web
PrintFriendly: Print Clean Pages, Save as PDF & Screenshot, AI Tools
* 4.6 out of 5. 4.8K ratings. Learn more about results and reviews. Details * Version. 6.11.14. * April 25, 2026. * 4.45MiB. * 54 ...


Chrome Web Store
PDF Tools for Documents and Web Pages - PrintFriendly
PDF Tools for Documents and Web Pages - PrintFriendly. ... * Convert & Merge. PDF Converter. Compress PDF. Merge PDF. * Convert fr...


PrintFriendly
Learning how to Save a Website as a PDF - Adobe
* /Adobe Acrobat. * /Resources. ... * /Adobe Acrobat. * /Resources. PDFs & E-signatures * Adobe Acrobat. * Acrobat Reader. View, s...


Adobe
Print HTML to PDF Free | Quick Browser Guide - Smallpdf
26 thg 2, 2026 — Work on the go with the Smallpdf Mobile App. * How To Print HTML Pages as PDFs. How To Print HTML Pages as PDFs * Open the web pag...


Smallpdf
How to save a PDF of a web page | Consumer Financial Protection ...
10 thg 2, 2023 — How to save a PDF of a web page. Looking to save a copy of a web page? Your web browser can likely “print” the page to a PDF. Both...

Consumer Financial Protection Bureau (.gov)
YouTube
28 thg 10, 2024 — Step 1. Open the Google Chrome app on your Android device. Step 2. Tap the vertical ellipses on this screen. A menu opens. Step 3.


# dung lenh cua js:

Để lưu hoặc in một trang web thành PDF bằng JavaScript, bạn có thể sử dụng phương thức mặc định của trình duyệt hoặc thư viện bên thứ ba nếu muốn tùy biến sâu hơn.
1. Sử dụng lệnh mặc định (Nhanh nhất)
Cách đơn giản nhất là gọi hộp thoại in của trình duyệt. Người dùng sẽ tự chọn "Lưu thành PDF" trong phần máy in.
javascript
window.print();
Hãy thận trọng khi sử dụng mã.
Ưu điểm: Không cần cài đặt, hoạt động trên mọi trình duyệt.
Nhược điểm: Phụ thuộc vào thao tác nhấn nút "Lưu" của người dùng.
2. Tự động hóa với thư viện (Chuyên nghiệp)
Nếu bạn muốn xuất PDF ngay lập tức khi nhấn nút mà không qua hộp thoại in, hoặc chỉ muốn in một phần cụ thể của trang web, hãy dùng các thư viện sau:
Cách A: Dùng html2pdf.js (Khuyên dùng vì dễ nhất)
Thư viện này sẽ chụp ảnh màn hình HTML và chuyển nó thành PDF.
Chèn thư viện:
html
<script src="https://cloudflare.com"></script>
Hãy thận trọng khi sử dụng mã.
Lệnh thực thi:
javascript
const element = document.getElementById('content'); // Chọn phần muốn in
html2pdf().from(element).save('tai-lieu.pdf');
Hãy thận trọng khi sử dụng mã.
Cách B: Dùng jsPDF kết hợp html2canvas
Dùng khi bạn cần tùy chỉnh vị trí, font chữ hoặc thêm nội dung vào PDF bằng code.
javascript
import { jsPDF } from "jspdf";

const doc = new jsPDF();
doc.text("Xin chào thế giới!", 10, 10);
doc.save("test.pdf");
Hãy thận trọng khi sử dụng mã.

3. Mẹo điều chỉnh giao diện khi in bằng CSS
Dù dùng lệnh nào, bạn nên thêm CSS này để bản PDF trông gọn gàng hơn (ẩn đi các nút bấm hoặc menu thừa):
css
@media print {
  .no-print {
    display: none; /* Những phần có class này sẽ không hiện trong PDF */
  }
  body {
    font-size: 12pt;
  }
}
Hãy thận trọng khi sử dụng mã.
Bạn muốn áp dụng lệnh này để in toàn bộ trang web hay chỉ một vùng dữ liệu nhất định (như hóa đơn, báo cáo)?





# hide page title
Để ẩn tiêu đề trang (thường là URL hoặc tiêu đề tài liệu xuất hiện ở đầu trang khi in PDF), bạn có hai cách tiếp cận chính:
1. Sử dụng CSS @media print (Dành cho window.print())
Mặc định, trình duyệt thường thêm thông tin tiêu đề và ngày tháng vào phần đầu trang (header). Bạn có thể yêu cầu trình duyệt xóa các lề này để ẩn chúng:
css
@media print {
  @page {
    /* Đặt lề bằng 0 để trình duyệt không có chỗ chèn tiêu đề/URL */
    margin: 0;
  }
  body {
    /* Sau đó thêm lề cho nội dung để văn bản không dính sát mép giấy */
    padding: 2cm;
  }
}
Hãy thận trọng khi sử dụng mã.
Lưu ý: Một số trình duyệt (như Chrome) vẫn có thể hiện tiêu đề nếu người dùng không tắt tùy chọn "Headers and footers" (Đầu trang và chân trang) trong hộp thoại in thủ công.
2. Sử dụng thư viện html2pdf.js (Triệt để nhất)
Nếu bạn dùng thư viện này, nó sẽ chụp lại nội dung HTML của bạn và vẽ lên một trang PDF mới. Cách này hoàn toàn không chứa tiêu đề trang web hay URL của trình duyệt.
javascript
const element = document.getElementById('content');
const opt = {
  margin:       1,
  filename:     'file-cua-ban.pdf',
  image:        { type: 'jpeg', quality: 0.98 },
  html2canvas:  { scale: 2 },
  jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
};

// Chạy lệnh này sẽ tải về PDF sạch sẽ, không có header/footer mặc định
html2pdf().set(opt).from(element).save();
Hãy thận trọng khi sử dụng mã.
3. Tạm thời đổi thẻ <title> (Mẹo cho window.print())
Nếu bạn vẫn muốn dùng window.print() nhưng muốn tiêu đề file hiển thị khác đi hoặc để trống, bạn có thể đổi tên trang web ngay trước khi in:
javascript
const originalTitle = document.title;
document.title = ""; // Để trống tiêu đề
window.print();
document.title = originalTitle; // Trả lại tên cũ sau khi in xong
Hãy thận trọng khi sử dụng mã.
Bạn đang muốn ẩn tiêu đề trang web mặc định của trình duyệt hay muốn ẩn một thẻ <h1> cụ thể có trong nội dung trang?





