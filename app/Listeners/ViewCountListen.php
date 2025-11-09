<?php

namespace App\Listeners;

use App\Helper\RedisHelper;
use App\Models\Api\PageApi;
use App\Models\Types\CategoryInterface;
use App\Models\Types\PageInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * không implements ShouldQueue là mặc định chạy đồng bộ.
 * dùng: env setting: QUEUE_CONNECTION=database để không phải xếp hàng(chạy không đồng bộ). 
 * $ php artisan queue:table
 * $ php artisan migrate
 */
class ViewCountListen implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The name of the connection the job should be sent to.
     * Đảm bảo trình điều khiển hàng đợi được định cấu hình trong tệp cấu hình / queue.php của bạn và trong tệp .env của bạn. 
     * Các trình điều khiển phổ biến bao gồm cơ sở dữ liệu, redis, beanstalkd hoặc sqs. Ví dụ: sử dụng Redis:
     * Để chạy không đồng bộ thì type sẽ phải là: redis|database|beanstalkd|sqs
     * còn sync sẽ luôn chạy đồng bộ.
     * run: php artisan queue:work database --queue=default (vì env đang đặt sync trong khi Lớp này đang để: "database")
     *
     * @var string|null
     */
    // public $connection = 'database'; // sync || database(async)

    /**
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public $delay = 2;

    /**
     * use indirectly redis default function.
     */
    protected $pageApi;

    /**
     * Create the event listener.
     */
    public function __construct(
        PageApi $pageApi,
    ) {
        $this->pageApi = $pageApi;
    }

    /**
     * Handle the event.
     * @param \App\Events\ViewCount $event
     */
    public function handle(object $event): void
    {
        /**
         * define key of value.
         */
        $target = $event->targetObject;
        $viewed = session('page_views', []);
        if (in_array($target->{PageInterface::ID}, $viewed)) {
            return;
        }
        /**
         * push: insert into array value of key
         * put: 
         */
        session()->push('page_views', $target->{PageInterface::ID});
        /**
         * dispatch action redis.
         */
        $this->pageApi->pageInfoActionRedis($target->{PageInterface::ID}, 'views',);
        /**
         * log message for test
         */
        // Log::info("saved value for page id: $target->id", $value);
    }

    /**
     * Determine whether the listener should be queued()
     * @return bool return true sẽ chạy: handle(), return false sẽ không chạy: handle().
     */
    public function shouldQueue(\App\Events\ViewCount $event): bool
    {
        return true;
        // return $event->targetObject >= 5000;
    }
}
