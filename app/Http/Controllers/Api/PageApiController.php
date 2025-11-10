<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\PageApi;
use App\Models\PageCategory;
use App\Models\Types\CategoryInterface;
use App\Models\Types\PageCategoriesInterface;
use Illuminate\Http\Request;

final class PageApiController extends Controller
{
	protected $pageApi;

	public function __construct(
		PageApi $pageApi,
	) {
		$this->pageApi = $pageApi;
	}

	public function related() {}

	/**
	 * 
	 */
	public function pageByIds(Request $request)
	{
		$pageIds = $request->get('ids');
		return $this->pageApi->pageByIds(explode(',', $pageIds));
	}

	/**
	 * get random pages.
	 */
	function pageRandom(Request $request)
	{
		return $this->pageApi->getRandom($request->get('limit', 6));
	}

	/**
	 * get sugget page by id
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function pageSugget(int $id)
	{
		/**
		 * @var \App\Models\Page $page
		 */
		$page = $this->pageApi->pageByIds([$id])->first();
		$categories = $page->categories->pluck(CategoryInterface::ID)->toArray();
		$pageIds = PageCategory::whereIn(PageCategoriesInterface::CATEGORY_ID, $categories)
			->whereNotIn(PageCategoriesInterface::PAGE_ID, [$id]) // exclude input showed id
			->latest(PageCategoriesInterface::PAGE_ID)
			->limit(6)
			->get(PageCategoriesInterface::PAGE_ID)
			->toArray();
		return $this->pageApi->pageByIds($pageIds);
	}

	/**
	 * return page has most os comment
	 * @return \App\Models\Page
	 */
	public function topComment() {
		$page = $this->pageApi->topComment();
		return $page;
	}

	/**
	 * get 3 newest page for TopPage Component.
	 */
	public function topPages() {
		return $this->pageApi->topPage();
	}
}
