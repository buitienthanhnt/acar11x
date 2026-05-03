Đôi điều về Laravel Model
Bài đăng này đã không được cập nhật trong 8 năm

Skip Model Event
Giả sử bạn có định nghĩa một event trong model User như sau:

    public static function boot() {
        self::updated(function() {
            \Log::info('User updated');
        });
    }

Tức là mỗi khi bạn gọi update một đối tượng user như sau

User::find($id)->update(['name' => 'New Name']);
/// hoặc là
$user = \App\User::find($id)
$user->name = 'New Name'
$user->save();

thì sẽ có một dòng log được ghi ra. Nhưng có 1 trường hợp bạn lại không muốn ghi log, vậy đâu là giải pháp, bạn có thể làm như sau:

User::where('id', $id)->update(['name' => 'New Name']);

Sẽ không có dòng log nào được ghi ra cả. Bạn có thể làm tương tự với delete:

User::where('id', $id)->delete();

Model Dirty
Đôi khi bạn muốn kiểm tra sự thay đổi giá trị của các attributes của một object model, hoặc kiểm tra giá trị của chúng trước khi thay đổi. Các bạn có thể làm như sau:

Ví dụ bạn có 1 user như sau:
$:~/workspace/laravel_dirty$ php artisan t
Psy Shell v0.8.11 (PHP 7.0.22-0ubuntu0.16.04.1 — cli) by Justin Hileman
>>> factory(App\User::class)->create();
=> App\User {#748
     name: "Santiago Block Sr.",
     email: "daniel.shannon@example.org",
     updated_at: "2017-09-26 10:38:29",
     created_at: "2017-09-26 10:38:29",
     id: 1,
   }

Kiểm tra xem có giá trị có bị thay đổi so với database ko?
>>> $user->name = 'Laravel'
=> "Laravel"
>>> $user->isDirty(); //Object có bị thay đổi ko?
=> true
>>> $user->isDirty('name'); //Kiểm tra attributes có bị thay đổi không?
=> true
>>> $user->isDirty('email');
=> false
>>> $user->isDirty(['email', 'name']); // isDirty() có thể nhận một array các attributes, chỉ cẩn có 1 attributes thay đổi thì kết quả cũng là true.
=> true

Có check thay đổi thì cũng có check giữ nguyên. isClean() là một hàm có ý nghĩa ngược với isDirty().

Lấy danh sách các tên attributes thay đổi và giá trị mới của chúng
>>> $user->getDirty();
=> [
     "name" => "Laravel",
   ]
>>> $user->email = 'laravel@gmail.com'
=> "laravel@gmail.com"
>>> $user->getDirty();
=> [
     "name" => "Laravel",
     "email" => "laravel@gmail.com",
   ]

Sau khi gán dữ liệu mới(chưa lưu vào db) thì làm thế nào để lấy được giá trị cũ.
>>> $user->name = 'Laravel'
=> "Laravel"
>>> $user
=> App\User {#728
     id: 1,
     name: "Laravel",
     email: "daniel.shannon@example.org",
     created_at: "2017-09-26 10:38:29",
     updated_at: "2017-09-26 10:38:29",
   }
>>> $user->getOriginal() //Lấy toàn bộ thông tin trước khi gán giá trị mới
=> [
     "id" => 1,
     "name" => "Santiago Block Sr.",
     "email" => "daniel.shannon@example.org",
     "password" => "$2y$10$JgByAKt7TZgpViK1bzIugOTJTrKWP2gexb03ff4S6.VjfBQ.cxk.K",
     "remember_token" => "IfShRj4InU",
     "created_at" => "2017-09-26 10:38:29",
     "updated_at" => "2017-09-26 10:38:29",
   ]
>>> $user->getOriginal('name') //Lấy toàn bộ thông tin trước khi gán giá trị mới của một attribute.
=> "Santiago Block Sr."
>>> 

