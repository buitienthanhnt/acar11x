<!-- lay lai code sau khi checkout branch
Để lấy lại code sau khi thực hiện git checkout, bạn cần xác định xem mình đang ở tình huống nào dưới đây. Git cung cấp các công cụ mạnh mẽ để khôi phục các thay đổi ngay cả khi bạn nghĩ rằng chúng đã mất.

1. Quay lại nhánh cũ (Code đã được commit)
Nếu bạn chỉ đơn giản là chuyển từ nhánh A sang nhánh B và muốn quay lại nhánh A để thấy code cũ:
Lệnh thực hiện: git checkout <tên_nhánh_cũ> hoặc git switch <tên_nhánh_cũ>.
Mẹo nhanh: Sử dụng git checkout - để quay lại nhánh bạn vừa rời khỏi ngay lập tức. 
ITviec
ITviec
 +1


2. Khôi phục các thay đổi chưa commit (Uncommitted changes)
Nếu bạn lỡ tay dùng git checkout . hoặc git checkout -- <file> và làm mất các thay đổi đang làm dở: 
Reddit
Reddit
Nếu bạn dùng VS Code: Nhấn Ctrl + Z trong file đó hoặc kiểm tra phần Timeline ở thanh bên trái để xem lịch sử lưu file cục bộ của trình soạn thảo.
Nếu bạn đã git add trước khi checkout: Bạn có thể tìm lại bằng lệnh git fsck --lost-found. Code sẽ nằm trong thư mục .git/lost-found/other.


3. Tìm lại commit "mất tích" (Sau khi reset hoặc xóa nhánh)
Nếu bạn đã lỡ tay xóa nhánh hoặc git reset --hard khiến code biến mất khỏi git log:
Sử dụng Reflog: Đây là "hộp đen" ghi lại mọi hành động của bạn trong Git.
Chạy lệnh **************: git reflog
Tìm dòng có nội dung code bạn cần (ví dụ: checkout: moving from master to dev).
Lấy mã hash (ví dụ: abc1234) và chạy: git checkout abc1234 hoặc git reset --hard abc1234. 
Refine
Refine


4. Lấy code từ một nhánh khác sang nhánh hiện tại
Nếu bạn đang ở nhánh mới và muốn lấy một vài file hoặc toàn bộ code từ nhánh cũ qua:
Lấy toàn bộ code từ nhánh khác: git merge <tên_nhánh_khác>.
Chỉ lấy một file cụ thể: git checkout <tên_nhánh_khác> -- <đường_dẫn_file>. 
Stack Overflow
Stack Overflow
 +1
Lưu ý quan trọng: Để tránh mất code trong tương lai, hãy luôn sử dụng git stash để tạm cất các thay đổi chưa commit trước khi chuyển nhánh. 
Reddit
Reddit
Bạn đang gặp vấn đề với việc vô tình ghi đè code hay chỉ đơn giản là muốn gộp code từ nhánh này sang nhánh kia?
Tìm giải pháp Git phù hợp
Bạn đang ở tình huống nào?
Chọn tình huống cụ thể để tôi hướng dẫn lệnh chính xác nhất cho bạn.
AI có thể mắc sai sót. Vì vậy, hãy xác minh câu trả lời -->
