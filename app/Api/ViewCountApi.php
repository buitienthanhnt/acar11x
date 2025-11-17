<?php

namespace App\Api;

use App\Events\ViewCount;
use App\Helper\LogHelper;
use App\Models\Category;
use App\Models\Page;
use App\Models\Types\PageInterface;

final class ViewCountApi
{

	protected LogHelper $logHelper;

	public function __construct(
		LogHelper $logHelper,
	) {
		$this->logHelper = $logHelper;
	}

	public function incrementViewCount(Page|Category $page): void
	{
		$sessionKey = null;
		switch (get_class($page)) {
			case Page::class:
				$sessionKey = 'page_views';
				break;
			case Category::class:
				$sessionKey = 'category_views';
				break;
			default:
				break;
		}
		if (!$sessionKey) {
			return;
		}
		/**
		 * check đã xem trang chưa.
		 * Luwu ý: session chỉ lưu trên web server, không lưu trên redis server.
		 * nên khi có nhiều web server thì session sẽ không đồng bộ.
		 * session không hoạt động trên queue hàng đợi vì về bản chất là nó đang chạy trên một tiến trình khác(cmd không đồng bộ)
		 */
		$viewed = session($sessionKey, []);
		if (in_array($page->{PageInterface::ID}, $viewed)) {
			$this->logHelper->logToday("skip save view count for page id: " . $page->{PageInterface::ID}, 'info', [
				'page_id' => $page->{PageInterface::ID},
			]);
			return;
		}
		/**
		 * push: insert into array value of key
		 * put: là gán giá trị cho key(sẽ thay thế giá trị cũ và gán bằng giá trị mới này).
		 */
		session()->push($sessionKey, $page->{PageInterface::ID});
		/**
		 * dispatch event for count of page view.
		 */
		ViewCount::dispatch($page);
	}
}
