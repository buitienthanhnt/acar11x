<?php 

// 1 cache;

/**
 *config in:  config/cache.php
 * get cache data:
 * $value = Cache::get('key');
 * set cache value:  Cache::put('key', 'value', 600); // 600 seconds || Cache::store('redis')->put('bar', 'baz', 600); // 10 Minutes(use redis driver)
 * Các phương thức incrementvà decrementcó thể được sử dụng để điều chỉnh giá trị của các mục số nguyên trong bộ nhớ đệm
 * Cache::increment('key', 5); // tăng giá
 * Lấy và Lưu trữ:
 * $value = Cache::remember('users', $seconds, function () {return DB::table('users')->get();}); || rememberForever
 */

// 2 collection:
/**
 * Lớp này Illuminate\Support\Collectioncung cấp một trình bao bọc (wrapper) lưu loát, tiện lợi để làm việc với các mảng dữ liệu
 * $collection = collect(['Taylor', 'Abigail', null])->map(function (?string $name) {
 * return strtoupper($name);
 * })->reject(function (string $name) {
 * return empty($name);
 * });
 * 
 * Như bạn có thể thấy, Collectionlớp này cho phép bạn nối các phương thức của nó để thực hiện ánh xạ và rút gọn mảng cơ bản một cách trôi chảy. 
 * Nhìn chung, các tập hợp là bất biến, nghĩa là mỗi Collectionphương thức trả về một thể hiện hoàn toàn mới Collection.
 * Các phương pháp có sẵn: https://laravel.com/docs/12.x/collections#available-methods
 * 
 * Kết quả của truy vấn Eloquent luôn được trả về dưới dạng Collectioncác phiên bản.
 */

// 3 Concurrency(async):
/**
 * https://laravel.com/docs/12.x/concurrency
 * 
 * Đôi khi bạn có thể cần thực thi nhiều tác vụ chậm không phụ thuộc lẫn nhau. 
 * Trong nhiều trường hợp, hiệu suất có thể được cải thiện đáng kể bằng cách thực thi đồng thời các tác vụ
 * Laravel đạt được tính đồng thời bằng cách tuần tự hóa các closure đã cho và phân phối chúng đến một lệnh Artisan CLI ẩn, 
 * lệnh này sẽ hủy tuần tự hóa các closure và gọi nó trong tiến trình PHP của chính nó
 * Sau khi closure được gọi, giá trị kết quả sẽ được tuần tự hóa trở lại tiến trình cha
 * 
 * Mặt Concurrencytiền hỗ trợ ba trình điều khiển: process(mặc định), fork, và sync.
 * composer require spatie/fork
 * 
 */
