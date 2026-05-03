<?php
Một chút mẹo PHP
Bài đăng này đã không được cập nhật trong 5 năm

Tình cờ mình có đọc được mấy thứ đối với mình khá hay ho nên cũng muốn chia sẻ lại ở đây, mai kia còn nhớ để mà dùng

# 1. if và else
Không sai khi sử dụng else và else if các khối trong mã của bạn, trên thực tế, chúng được tạo ra để sử dụng, tuy nhiên, trong một số trường hợp, các khối này trở nên thừa. Hãy xem một ví dụ sau:

function gender(bool $userIsMale) {
    if ($userIsMale) {
        return "User is male";
    } else {
        return "User is female";
    }
}

Trong trường hợp này, hàm gender trả về một đầu ra được đặt dựa trên $userIsMale là true hay false. Khi return được sử dụng trong một hàm, bất kỳ mã nào bên dưới câu lệnh return sẽ bị bỏ qua hoàn toàn, vì vậy, nếu $userIsMale đúng, thì khối else sẽ bị bỏ qua vì một giá trị được trả về. Với khái niệm này, chúng ta có thể loại bỏ khối else như thế này:

function gender(bool $userIsMale) {
    if ($userIsMale) {
        return "User is male";
    }

    return "User is female";
}

# 2. if: less & more
Mẹo 2 xây dựng dựa trên mẹo mà chúng ta vừa xem xét ở trên nhưng đi sâu hơn một chút. Trong if/else hoặc thậm chí sử dụng một ví dụ như mẹo 1, bạn có thể có các điều kiện trong đó một block, hoặc if hoặc else, có ít mã hơn khối kia. Trong những tình huống như vậy, tốt hơn là nên xử lý khối ít mã hơn trước. Hãy xem một ví dụ thực tế.

public function categoryWithPosts($category)
{
    $category = Category::find($category);

    if ($category) {
        $category->posts = $category->posts()->published()->get();
   
        return response(['data' => $category], 200);
    } else {
        return response(['error' => 'Category not found'], 404);
    }
}

Đoạn mã ở trên kiểm tra danh mục bài đăng và chạy một điều kiện dựa trên việc danh mục đó có được tìm thấy hay không. Nếu chúng ta chỉ sử dụng mẹo 1, chúng ta sẽ có mã trông như thế này:

public function categoryWithPosts($category)
{
    $category = Category::find($category);

    if ($category) {
        $category->posts = $category->posts()->published()->get();
        // có thêm bất kỳ mã nào ở đây sẽ
        // phình to phần này của hàm
       
        return response(['data' => $category], 200);
    }

    return response(['error' => 'Category not found'], 404);
}

Mã này đúng, tuy nhiên, bạn có thể thấy rõ rằng mã chính của chúng tôi được bọc bởi {} và đẩy sâu hơn. Nếu mã này dài hơn đáng kể, sẽ rất khó để giữ tất cả trong khối if. Làm theo mẹo 2, chúng ta có thể có cái này thay thế:

public function categoryWithPosts($category)
{
    $category = Category::find($category);

    if (!$category) {
        return response(['error' => 'Category not found'], 404);
    }

    $category->posts = $category->posts()->published()->get();
    // chúng ta có thể tự do có thêm mã ở đây
    // mà không cần lo lắng về mã trông như nào
    
    return response(['data' => $category], 200);
}

Vì khối khác có ít mã hơn, chúng tôi sử dụng một câu lệnh phủ định với ! để làm cho mã đó chạy trước. Vì vậy, nếu đúng hơn của chúng tôi chứa if not category, run code.... Điều này cho phép chúng ta có thêm không gian để xử lý mã chính của mình một cách tự do.

# 3. Xác minh nhiều chuỗi
Giả sử chúng ta muốn tìm xem một biến nhất định có phải là một trong nhiều chuỗi hay không, chúng ta rõ ràng phải viết một loạt các câu lệnh điều kiện để xác minh điều này:

$item = "candy";

switch ($item) {
    case 'candy':
        return true;
    case 'toy':
        return true;
    default:
        return false;
}

// không thêm break vì đang sử dụng return

// or
if ($item == 'candy' || $item == 'toy') {
    return true;
}

return false;

Mã này trả về false nếu biến item không phải candy cũng không toy. Điều này hoàn toàn chính xác, tuy nhiên, điều này rất lặp đi lặp lại. Thay vào đó, chúng ta có thể kiểm tra một mảng cho chuỗi mà chúng ta muốn tìm:

if (in_array($item, ["candy", "toy"])) {
    return true;
}

return false;

Thậm chí điều này có thể được rút ngắn hơn nữa vì in_array trả về một boolean.

return in_array($item, ["candy", "toy"]);

Chúng ta chỉ rút ngắn những dòng này xuống chỉ còn một dòng, rõ ràng phải không? Cái này hoạt động ra sao? Chúng tôi có một mảng chứa các chuỗi mà chúng tôi muốn kiểm tra. Sau đó, chúng tôi chuyển nó vào in_array. Điều này tạo ra một điều kiện đơn giản như:

if $item is inside the array holding "candy" and "toy", return true, else false

Bạn có thể tự hỏi, tại sao không trả lại trực tiếp cho dù $item là kẹo hay đồ chơi vì đó cũng chỉ là một dòng, như thế này:

return ($item == 'candy' || $item == 'toy');


Điều này sẽ cho chúng ta cùng một kết quả, tuy nhiên, giả sử chúng ta đang kiểm tra 10 chuỗi:

return ($letter == 'a' || $letter == 'b' || $letter == 'c' || $letter == 'd' ...);

Bạn có thể thấy rõ rằng điều này dễ dàng vượt qua khỏi tầm tay, so với điều này:

return in_array($letter, ["a", "b", "c", "d", ...]);

Lưu ý rằng tham số đầu tiên in_array là chuỗi mà chúng tôi đang thực sự kiểm tra

# 4. ??
?? có lẽ là cách dễ nhất để tạo điều kiện nội tuyến mà không có 2 phần. Hãy xem ví dụ nhé

$data = [
    "a" => 1,
    "b" => 2,
    "c" => null,
];

return $data["c"] ? $data["c"] : "No data";

Dòng cuối cùng ở đây kiểm tra xem khóa ctrong $data có trung thực hay không, nếu không, nó trả về "No data"

Chúng ta có thể viết lại dòng cuối cùng với ?? trông như thế này:

// ...
return $data["c"] ?? "No data";

Trong trường hợp này ?? hoạt động giống như toán tử || trong các ngôn ngữ khác. Một ví dụ trong thế giới thực về điều này sẽ như thế này:

$user = getUserFromDb($user_id) ?? trigger_error("User id is invalid");

echo $user;

getUserFromDb là trả về một người dùng từ cơ sở dữ liệu ở đâu đó, tuy nhiên, nếu người dùng không được tìm thấy, thay vì đặt biến người dùng, chúng tôi ngắt ứng dụng bằng trigger_error. Nếu không, ?? sẽ phải viết điều này thay thế:

$user = getUserFromDb($user_id);

if (!$user) {
    trigger_error("User id is invalid");
}

echo $user;

# 5. Tính đệ quy trên lặp lại
Tôi nghĩ mẹo này khá đơn giản, hãy cố gắng sử dụng đệ quy thay vì lặp lại nhiều lần. Có những tình huống khiến bạn lặp lại một số mã, điều đó không sao cả, nhưng nếu bạn thấy mình đang lặp lại cùng một mã, chỉ cần đặt nó thành một phương pháp. Tính đệ quy đi vào đâu? Hãy xem một ví dụ:

Đây là một phương thức đã viết cho đối tượng yêu cầu của khung công tác Lá của tôi , để trả về một trường cụ thể được chuyển vào yêu cầu.

/**
 * Returns request data
 *
 * This methods returns data passed into the request (request or form data).
 * This method returns get, post, put patch, delete or raw faw form data or NULL
 * if the data isn't found.
 *
 * @param string|array $params The parameter(s) to return
 * @param bool $safeData Sanitize output
 */

Điều này có nghĩa là phương thức này có thể nhận vào một mảng hoặc chuỗi và dựa trên đầu vào, nó sẽ trả về một chuỗi hoặc một mảng. Giải pháp sẽ là kiểm tra xem đầu vào có phải là một mảng hay không, lặp qua nó để lấy các chuỗi trong mảng sau đó thực hiện tìm nạp dữ liệu trên các chuỗi đó, trông như thế này.

public function get($params, bool $safeData = true)
{
    if (is_string($params)) return $this->body($safeData)[$params] ?? null;

    $data = [];
    foreach ($params as $param) {
        $data[$param] = $this->body($safeData)[$params] ?? null;
    }
    return $data;
}

Ở đây, bạn nhận thấy $this->body($safeData)[$params] ?? null đang được lặp lại, không chỉ vậy, nhưng điều gì sẽ xảy ra nếu một mảng chứa một mảng khác được chuyển vào thay thế. Vì đây là một thư viện, nên không thể biết người dùng sẽ chuyển những thứ gì vào đó, vì vậy tôi đã làm điều này thay thế.

public function get($params, bool $safeData = true)
{
    if (is_string($params)) return $this->body($safeData)[$params] ?? null;

    $data = [];
    foreach ($params as $param) {
        $data[$param] = $this->get($param, $safeData); // I called the function again
    }
    return $data;
}

Điều này đảm bảo rằng cho đến khi giá trị được lặp lại là một chuỗi, nó sẽ không cố gắng tìm nạp dữ liệu của nó. Một thủ thuật nhỏ so với những cách trên, nhưng chắc chắn hữu ích. Lưu ý rằng chức năng này có phạm vi lớp, do đó sử dụng $this

# 6. PHP + HTML
Điều này dành cho khi bạn muốn viết PHP trong HTML hoặc HTML trong PHP😅 của bạn. Chúng tôi thường làm những việc như:

<?php
foreach ($items as $item) {
    echo '
        <div class="product__card">
            <h3>{$item->name}</h3>
        </div>
    ';
}
?>

Mặc dù điều này là tốt, nhưng bạn có thể thấy rõ ràng, chúng tôi đang xuất HTML dưới dạng một chuỗi. HTML càng cồng kềnh, càng trở nên căng thẳng hơn trong việc khớp các thẻ và theo dõi chính xác phần HTML mà chúng ta đang viết. Có một giải pháp gọn gàng cho việc này.

<?php foreach ($items as $item): ?>
    <div class="product__card">
        <h3><?php echo $item->name; ?></h3>
    </div>
<?php endforeach; ?>

Bạn có thể thấy rõ chúng tôi đang duy trì định dạng HTML và căn chỉnh mã của mình như thế nào ... và không, đây không phải là một công cụ tạo khuôn mẫu, đây chỉ là PHP làm cho mọi thứ trở nên đơn giản đối với chúng tôi. Một điều quan trọng về PHP là cách nó cho phép thực hiện cùng một thứ theo nhiều cách khác nhau. Trong ví dụ ở trên, chúng tôi đang sử dụng:

foreach (...):
// code
endforeach;

// also works with if
if (...):
// code
endif;

// also
if (...) #one line code

while():
// ...
endwhile;

# 7. Viết các khối chức năng
Các khối chức năng có thể bao gồm từ một tính năng lớn đến một trình bao bọc đơn lẻ, xung quanh một hàm PHP mặc định, vấn đề chỉ là tạo khối chức năng đó. Điều này không chỉ để tránh lặp lại mà còn để tăng tốc quy trình làm việc của bạn và làm cho mã của bạn dễ đọc hơn.

Bạn có thể viết một phương thức đơn giản để tạo một chuyển hướng như sau:

function redirectTo($route) {
    header("location: $route", true, 302);
}

Vì vậy, thay vì viết header("location: /home", true, 302) mọi lúc, viết redirectTo("/home") sẽ có ý nghĩa hơn. Điều tương tự cũng áp dụng cho các thư viện của bên thứ 3 và các quy trình dài, viết một khối mã có thể tái sử dụng theo cách mở, ví dụ:

UserNotification::send($user_id, $notification);

rõ ràng là tốt hơn so với việc viết một loạt các dòng mỗi khi bạn phải gửi thông báo cho người dùng. Một mẹo rất nhỏ nhưng rất hữu ích khác.

# 8. Sử dụng Types
Một cái khác đơn giản. Đây là một trong những tính năng ít được sử dụng nhất, nhưng rất mạnh mẽ có sẵn trong PHP. Đây là một tính năng có thể giúp bạn và các nhà phát triển khác đỡ căng thẳng hơn rất nhiều (nếu bạn làm việc với một nhóm).

Tất nhiên, bạn có thể viết mô tả hàm như ví dụ trong mẹo 5 ở trên, nhưng việc viết mô tả hàm cho tất cả các hàm và biến của bạn trong một dự án lớn sẽ trở thành một nhiệm vụ khá khó khăn.

Hãy xem cách các loại có thể cứu mạng chúng ta sau:

function getItem($item) {
    // $item is expected to be an array
    // for whatever reason
    return allItems()[$item[0]];
}

Nếu một nhà phát triển khác làm việc trong dự án hoặc thậm chí chính bạn sau một vài tuần, nhìn thấy phương thức getItem, biến $item ở đó rõ ràng được mong đợi là một chuỗi, nhưng hàm được viết để xử lý một mảng.

Điều nguy hiểm ở đây là việc truyền vào một chuỗi sẽ không làm hỏng ứng dụng, nó vẫn chạy hoàn hảo. Tại sao?

Nếu "chair" được chuyển vào hàm, nó sẽ được đánh giá allItems()["c"], điều này sẽ dẫn đến lỗi khiến bạn phải thức dậy lúc 12 giờ sáng😅. Điều này có thể dễ dàng tránh được như thế này:

function getItem(array $item) {
    return allItems()[$item[0]];
}

Điều này sẽ đảm bảo rằng bất cứ thứ gì được chuyển vào đây đều là loại cần thiết.

Bạn cũng có thể sử dụng các phương pháp như is_string và is_array, chúng tôi đã thấy ở trên như thế này:

function getItem($item) {
    if (!is_array($item)) throwErr("item should be array");

    return allItems()[$item[0]];
}

# 9. Đừng chỉ viết mã!
Được rồi, đây là một mẹo thưởng. Nó áp dụng cho không chỉ PHP, mà về mặt kỹ thuật hầu hết mọi ngôn ngữ/khuôn khổ mà bạn làm việc. Ý tôi là không chỉ mã là tương đối đơn giản.

Giả sử bạn muốn viết một phương thức yêu cầu thanh toán từ tài khoản của người dùng, việc chuyển thẳng vào mã hóa tính năng này có thể (hoặc không) cuối cùng khiến bạn bối rối tại một số điểm, nơi bạn sẽ phải dừng lại, cuộn sao lưu, kiểm tra một cái gì đó từ một tệp ở đâu đó hoặc một cái gì đó tương tự.

Tôi đang đề xuất điều gì? Đây:

// in class scope
public function requestPayout()
{
    // parse token to get user id

    // fetch user from DB with id

    // check if the user is eligible for payouts

    // get user balance with user helper

    // check and throwErr for insufficient funds

    // ...
}

Ở trên chỉ cho phép bạn thực hiện tất cả các suy nghĩ cần thiết trước khi thực sự bắt đầu viết bất kỳ tính năng nào. Nó cũng theo một cách nào đó giúp bạn kiểm tra chéo những gì bạn đang xây dựng vì cuối cùng bạn sẽ liệt kê ra tất cả các quy trình của mình trước.

Bài viết mình đọc đường từ link gốc này nha https://dev.to/mychi_darko/php-tips-and-tricks-4kpn
