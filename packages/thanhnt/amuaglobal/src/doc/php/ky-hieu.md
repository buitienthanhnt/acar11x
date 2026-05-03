<?php

Ký hiệu & trong PHP
Bài đăng này đã không được cập nhật trong 8 năm

Là môt lập trình viên PHP (có thể cả ngôn ngữ khác) chắc các bạn không xa lại gì với ký hiệu &. Nhưng bạn có chắc là đã sử dụng hết tình năng của nó không? Vậy cùng điểm lại nhé.

Toán tử thao tác bit AND(AND Bitwise Operators)

Toán tử thao tác bit AND lấy 2 toán hạng nhị nhân có chiều dài bằng nhau và thực hiện phép toán lý luận AND trên mỗi cặp bit tương ứng bằng cách nhân chúng lại với nhau. Nhờ đó, nếu cả 2 bit ở vị trí được so sánh đều là 1, thì bit hiển thị ở dạng nhị phân sẽ là 1 (1 x 1 = 1); ngược lại thì kết quả sẽ là 0 (1 x 0 = 0)

Trong php và nhiều ngôn ngữ khác, & là toán tử thao tác bit AND.

 14 = 1110
  7 = 0111
 ---------
 14 & 7 = 0110 = 6

Và ta có $a &= $b là các viết ngắn gọn $a = $a & $b

Truyền tham chiếu

Chúng ta cùng nhắc lại khái niệm tham chiếu và tham trị:

Truyền tham trị: trình biên dịch sẽ thực hiện tạo một bản sao và truyền bản sao đó vào nơi nó phải tới. Do đó mọi tác động, thay đổi trên bản sao này đều không ảnh hưởng tới biến gốc. Và khi kết thúc bản sao này sẽ được giải phóng.

<?php
function count($var) {
    $var = $var + 1;
}

$a=5;
count($a);
echo $a;
// kết quả sẽ là 5
?>

Truyền tham chiếu: thì nó sẽ truyền địa chỉ, vị trí trên bộ nhớ của chính cái biến ta đang muốn truyền. Vì là mọi hành động tác động thông qua một địa chỉ nhớ nên mọi dữ liệu ta thay đổi thì biến bên ngoài cũng thay đổi theo.

<?php
function count(&$var) {
    $var = $var + 1;
}

$a=5;
count($a);
echo $a;
// kết quả sẽ là 6
?>

Đôi khi bạn có thể bắt gặp các viết như sau $a =& $b, điều đó có nghĩa là gán biến $a tham chiếu đến địa chỉ của $b

Toán tử logic AND(AND LOGIC)

Có vẻ đây là cách sử dụng phổ biến nhất của ký hiệu &, ta có bảng chân lý sau:

 $a     $b     $a && $b
false  false    false
false  true     false
true   false    false
true   true     true

Nói đơn giản là $a && $b đúng khi và chỉ khi $a và $b đều đúng.

Tài liệu tham khảo:

PHP Doc
Reference - What does this symbol mean in PHP? (https://stackoverflow.com/questions/3737139/reference-what-does-this-symbol-mean-in-php?rq=1)
