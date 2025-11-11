<?php

namespace App\Models\Api;

use App\Enums\CacheEnum;
use App\Helper\RedisHelper;
use App\Models\Category;
use App\Models\Page;
use App\Models\PageContent;
use App\Models\Types\CategoryInterface;
use App\Models\Types\PageContentInterface;
use App\Models\Types\PageInterface;
use App\Models\Types\TagInterface;
use App\Models\Types\WriterInterface;
use App\Models\Writer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PageApi
{
	/**
	 * @var \App\Models\Page $page
	 */
	protected $page;

	/**
	 * @var \App\Models\Tag $tag
	 */
	protected $tag;

	protected $category;

	/**
	 * \Illuminate\Http\Request $request
	 */
	protected $request;

	protected $cacheHelper;

	function __construct(
		\Illuminate\Http\Request $request,
		\App\Helper\CacheHelper $cacheHelper,
		\App\Models\Page $page,
		\App\Models\Tag $tag,
		\App\Models\Category $category,
	) {
		$this->request = $request;
		$this->cacheHelper = $cacheHelper;
		$this->page = $page;
		$this->tag = $tag;
		$this->category = $category;
	}

	/**
	 * get all items of paper
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function listPage()
	{
		return $this->page->all();
	}

	/**
	 * default paginate.
	 * @var int $limit
	 * @return \Illuminate\Pagination\LengthAwarePaginator
	 */
	public function pagePaginate($limit = 12)
	{
		/**
		 * "page-list.{limit}.{page}.{order}.{sort}"
		 * "page-list.12.1.id.asc"
		 */
		$cache_key = implode('.', [
			"page-list",
			$limit,
			$this->request->get('page', 1),
			$this->request->get('order', 'id'),
			$this->request->get('sort', ""),
		]);

		/**
		 * cache_key
		 * cache_time: second(đơn vị tính bằng giây)
		 * cache_callback
		 */
		return Cache::remember($cache_key, 1 * 60 * 60, function () use ($limit) {
			return $this->page
				->with('source')
				->withCount(['comments'])
				->paginate($limit);
		});
	}

	/**
	 * get list page item and filter action.
	 * @param int $limit 
	 */
	public function pageFilterPaginate(int $limit = 12)
	{
		$requestParam = $this->request->all();
		/**
		 * define for values.
		 */
		$allPages = collect();
		$hasFilter = false;
		$cache_key = implode(".", [
			"page-list",
			$limit,
			$this->request->get('page', 1),
			$this->request->get('order', 'id'),
			$this->request->get('sort', 'asc'),
		]);

		/**
		 * filter pages by categories.
		 */
		if (isset($requestParam[CategoryInterface::CATEGORY_FILTER_KEY]) && $cateId = $requestParam[CategoryInterface::CATEGORY_FILTER_KEY]) {
			$pageByCategory = $this->pageByCategoryId($cateId)->get();
			$allPages = $allPages->merge($pageByCategory->pluck(PageInterface::ID));
			$hasFilter = true;
			$cache_key .= '.' . $cateId;
		}

		/**
		 * filter by writer
		 */
		if ($writerFilter = $this->request->get(WriterInterface::WRITER_FILTER_KEY)) {
			$pageByWriters = Page::where(PageInterface::WRITER, '=', $writerFilter)->get()->pluck(PageInterface::ID);
			$allPages = $allPages->count() ? $allPages->intersect($pageByWriters) : $allPages->merge($pageByWriters);
			$hasFilter = true;
			$cache_key .= '.' . $writerFilter;
		}

		/**
		 * filter by content type.
		 */
		if ($typeFilter = $this->request->get(PageContentInterface::TYPE_FILTER_KEY)) {
			$pageByFilters = $this->pageByType(PageContentInterface::TYPE_FILTER_KEY, $typeFilter)->get()->pluck(PageInterface::ID);
			$allPages = $allPages->count() ? $allPages->intersect($pageByFilters) : $allPages->merge($pageByFilters);
			$hasFilter = true;
			$cache_key .= '.' . $typeFilter;
		}

		if ($hasFilter) {
			/**
			 * get paginate pages by filter values.
			 * cache_key
			 * cache_time: second(đơn vị tính bằng giây)
			 * cache_callback
			 */
			return Cache::remember($cache_key, 1 * 60 * 60, function () use ($allPages, $limit) {
				return Page::whereIn(PageInterface::ID, $allPages->toArray())
					->with('source')
					->withCount(['comments'])
					->paginate($limit);
			});
		}
		return $this->pagePaginate($limit);
	}

	/**
	 * @param int $limit
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function pageRandom(int $limit = 6)
	{
		/**
		 * không nên dùng: $this->page->all()->random($limit);
		 * Vì hàm: random sẽ báo lỗi nếu số lượng không đủ.
		 */
		return $this->page::inRandomOrder()->take($limit)->get();
	}

	/**
	 * @param int $categoryId
	 * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function pageByCategoryId(int $categoryId)
	{
		$category = $this->category->find($categoryId);
		return $category ? $category->pages() : null;
	}

	/**
	 * @return Illuminate\Database\Eloquent\Builder
	 */
	public function pageByType(string $type, $value)
	{
		$pageIds = PageContent::where($type, $value)
			->get(PageContentInterface::PAGE_ID)
			->unique(PageContentInterface::PAGE_ID)
			->pluck(PageContentInterface::PAGE_ID)
			->toArray();
		return Page::whereIn(PageInterface::ID, $pageIds);
	}

	/**
	 * filter page by writer
	 * @param int $writerId
	 * @return Illuminate\Database\Eloquent\Builder
	 */
	public function pageByWriter(int $writerId)
	{
		// call ->get() to return collection.
		return Page::where(PageInterface::WRITER, $writerId);
	}

	/**
	 * @return \Illuminate\Support\Collection<TKey, TMapValue>|static<TKey, TMapValue>
	 */
	public function pageFilters()
	{
		$pageFilters = [];
		$pageFilters[] = $this->pageFilterType();
		$pageFilters[] = $this->pageFilterCategories();
		$pageFilters[] = $this->pageFilterWriters();
		$pageFilters[] = $this->pageSortFilter();
		$pageFilters[] = $this->pageOrderFilter();
		return $pageFilters;
	}

	/**
	 * list of sort filter value
	 * @return array
	 */
	protected function pageSortFilter(): array
	{
		return [
			'label' => 'sort',
			'type' => 'sort',
			'data' => [
				['value' => 'asc', 'label' => 'asc', 'selected' => strtolower($this->request->get('sort')) === 'asc'],
				['value' => 'desc', 'label' => 'desc', 'selected' => strtolower($this->request->get('sort')) === 'desc'],
			],
		];
	}

	/**
	 * list of sort filter attribute value
	 * @return array
	 */
	protected function pageOrderFilter(): array
	{
		return [
			'label' => 'order by',
			'type' => 'order',
			'data' => [
				['value' => 'id', 'label' => 'id', 'selected' => strtolower($this->request->get('order')) === 'id'],
				['value' => 'title', 'label' => 'title', 'selected' => strtolower($this->request->get('order')) === 'title'],
				['value' => 'created_at', 'label' => 'created_at', 'selected' => strtolower($this->request->get('order')) === 'created_at'],
			],
		];
	}

	/**
	 * get page filter of page content type.
	 */
	protected function pageFilterType()
	{
		$filters = $this->request->all();
		/**
		 * select(c1, c2, c3)->distinct(): lấy duy nhất không trùng lặp.
		 * not use: ->unique(c1) because it not work.
		 */
		$types = PageContent::select(PageContentInterface::TYPE)->distinct()->get()->map(function ($item) use ($filters) {
			return [
				'value' => $item->{PageContentInterface::TYPE},
				'label' => __("attr.type.$item->type"),
				'selected' => in_array(PageContentInterface::TYPE, array_keys($filters)) && ($item->{PageContentInterface::TYPE} == $filters[PageContentInterface::TYPE])
			];
		})->values();

		return [
			'label' => 'type',
			'type' => PageContentInterface::TYPE_FILTER_KEY,
			'data' => $types,
		];
	}

	/**
	 * get page filter of categories.
	 * @return array
	 */
	protected function pageFilterCategories()
	{
		/**
		 * get flat category tree for select filter by categories.
		 * @var array $flatCategoryTree
		 */
		$flatCategoryTree = $this->cacheHelper->saveAndReturn(CacheEnum::FlatCategoryTree->value, 60 * 15, callback: fn() => Category::getCategoryTree(prefix: ''));
		return [
			'label' => 'categories',
			'type' => CategoryInterface::CATEGORY_FILTER_KEY,
			'data' => array_map(function ($category) {
				return [
					...$category,
					'selected' => $category['value'] == $this->request->get(CategoryInterface::CATEGORY_FILTER_KEY),
				];
			}, $flatCategoryTree)
		];
	}

	/**
	 * @return array
	 */
	protected function pageFilterWriters()
	{
		$filters = $this->request->all();
		/**
		 * @var \Illuminate\Database\Eloquent\Collection|static[] $activeWriter
		 * select unique(distinct: WRITER id)  
		 * vendor/laravel/framework/src/Illuminate/Database/Eloquent/Concerns/QueriesRelationships.php
		 */
		$activeWriter = $this->cacheHelper->saveAndReturn('activeWriter', 60 * 20, callback: fn() => Writer::select(PageContentInterface::ID, WriterInterface::NAME)->whereHas('pages', callback: fn($query) => $query->select('id'))->get());
		$types = $activeWriter->map(function ($item) use ($filters) {
			return [
				'value' => $item->{PageContentInterface::ID},
				'label' => $item->{WriterInterface::NAME},
				'selected' => isset($filters[WriterInterface::WRITER_FILTER_KEY]) ? $item->{WriterInterface::ID} == $filters[WriterInterface::WRITER_FILTER_KEY] : false,
			];
		})->values();

		return [
			'label' => 'writers',
			'type' => 'writer',
			'data' => $types,
		];
	}

	/**
	 * action for register new page
	 * @param array $data
	 * @return bool
	 */
	function register($data = [])
	{
		/**
		 * format data for model factory by : FILLED_FILEDS và $data input.
		 */
		$modelData = array_intersect_key( // so sánh 2 mảng và trả về mảng có khóa chung
			$data,
			array_flip($this->page::FILLED_FILEDS) // đảo ngược khóa và gía trị. trong mảng 1 chiều nó sẽ nhận giá trị là index số : 0,1,2,3
		);
		return $this->page->factory()->create($modelData)->save();
	}

	/**
	 * @param array $ids
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function pageByIds(array $ids)
	{
		/**
		 * sau khi goi: where() -> $queryBuilder -> get() -> $collection
		 */
		return $this->page->whereIn(PageInterface::ID, $ids)->get();
	}

	/**
	 * @param int|Page $param
	 * @return \App\Models\Page
	 */
	public function detailById(int $param)
	{
		if ($param instanceof \App\Models\Page) {
			$param = $param->{PageInterface::ID};
		}
		/**
		 * get page by alias(first of paper by alias)
		 * inject with writer model, page content, tags data, category value in list value
		 * done!
		 */
		return $this->cacheHelper->saveAndReturn('page_detail_' . $param, 60 * 60, function () use ($param) {
			return $this->page->where(PageInterface::ID, '=', $param)
				->with('pageContents')
				->with('tags')
				->with('categories')
				->with('writer')
				->get()
				->first();
		});
	}

	/**
	 * @param string $key
	 * @param string|bool|mixed $value
	 * @return \App\Models\Page|null
	 */
	public function detailByAttr(string $key, $value)
	{
		return $this->cacheHelper->saveAndReturn('p_' . $key . "=" . $value, 60 * 60, function () use ($key, $value) {
			return $this->page->where($key, '=', $value)
				->with('pageContents')
				->with('tags')
				->with('categories')
				->with('writer')
				->with('source')
				->get()
				->first();
		});
	}

	/**
	 * @param string $tag
	 * @return Illuminate\Pagination\LengthAwarePaginator
	 */
	public function pageByTag(string $tag)
	{
		$cache_key = "tag.$tag." . $this->request->get('page', 1);
		return Cache::remember($cache_key, 1 * 60 * 60, function () use ($tag) {
			$listTags = $this->tag->where(TagInterface::KEY, '=', $tag)->get(TagInterface::TARGET_ID);
			return $this->page->whereIn(PageInterface::ID, $listTags)->paginate(12);
		});
	}

	/**
	 * @param int $limit
	 * @param array $excludes
	 * @return  @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getRandom(int $limit = 6, array $excludes = [])
	{
		/**
		 * inRandomOrder: get random page(vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php)
		 * take(6): get 6 items 
		 */
		return $this->page->inRandomOrder()->take(6)->get();
		// return $this->page->fromQuery("SELECT * FROM pages ORDER BY RAND() LIMIT 6");
		// return $this->page->all()->random($limit);
	}

	/**
	 * return custom support fields for page content.
	 */
	public function customFields(): array
	{
		return [
			[
				'label' => 'tác giả(select)',
				'type' => 'select',
				'path' => WriterInterface::WRITER_FILTER_KEY,
				'options' => Writer::writerOptions()->toArray(),
			],
			[
				'label' => 'danh mục(timeline)',
				'type' => 'timeline',
				'path' => CategoryInterface::CATEGORY_FILTER_KEY,
				'options' => Category::getCategoryTree(prefix: ''),
			]
		];
	}

	/**
	 * @param int $pageId
	 * @param string $type views|star|like|heart|fire
	 * @param string $action inc|dic
	 * @return array|null
	 */
	public static function pageInfoActionRedis(int $pageId, string $type = 'views', $action = 'inc')
	{
		$key = "page:$pageId";

		/**
		 * set value for key before.
		 * $value = ['views' => int, 'liked' => int, 'heart' => int, 'star' => int,'fire' => int, ];
		 */
		$value = [];
		if ($lastValue = RedisHelper::getValue($key)) {
			$value = json_decode($lastValue, true);
			if ($action === 'inc') {
				$value[$type] = isset($value[$type]) ? $value[$type] + 1 : 1;
			} else {
				$value[$type] = isset($value[$type]) && $value[$type] > 1 ? $value[$type] - 1 : 0;
			}
		} else {
			if ($action === 'inc') {
				$value[$type] = 1;
			} else {
				return null;
			}
		}
		/**
		 * save page info to redis server.
		 */
		RedisHelper::setValue($key, json_encode($value));
		return $value;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function topPage()
	{
		/**
		 * get 3 newest page 
		 * is [ABOVE] pages
		 * ->latest(): sắp xếp mới nhất theo cột
		 * ->limit(): lấy mấy giá trị
		 * ->get(): trả về collection.
		 * ->makeHidden: ẩn bớt 1 số thuộc tính.
		 */
		return $this->cacheHelper->saveAndReturn(
			CacheEnum::TopPage->value,
			60 * 30,
			fn() => $this->page->newQuery()
				->where(PageInterface::ABOVE, '=', true)
				->latest('created_at')
				->limit(3)
				->get()
				->makeHidden([PageInterface::ACTIVE, PageInterface::WRITER, PageInterface::DESCRIPTION, 'info'])
		);
	}

	/**
	 * return list page with only type of filter value.
	 * @param string $type
	 * @param int $limit
	 * @return @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function pageFilterByType(string $type, int $limit = 6)
	{
		return $this->page::whereRelation('pageContents', PageContentInterface::TYPE, $type)
			->latest('created_at')
			->distinct('id')
			->limit($limit)
			->with(['pageContents' => function ($query) use ($type) {
				$query->where(PageContentInterface::TYPE, $type);
			}])
			->get();
	}

	/**
	 * filter timeValue of string json value.
	 * @param string $type
	 * @param int $limit
	 * @return static[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function pageFilterTimeline(string $type = 'timeline', int $limit = 6)
	{
		/**
		 * support for mysql and sqlite.
		 */
		$connection = array_keys(DB::getConnections())[0];
		$exjson = $connection === 'mysql' ? 'JSON_EXTRACT' : 'json_extract';
		$timeSql = $connection === 'mysql' ? 'DATE_FORMAT' : 'STRFTIME';
		$timeFormat = $connection === 'mysql' ? '%Y-%m-%d %h:%i:%s' : '%Y-%m-%d %H:%M:%S';
		$now = $connection === 'mysql' ? 'NOW()' : "datetime('now')";
		$timeValueRaw = function ($input) use ($connection, $timeSql, $exjson, $timeFormat) {
			return $connection === 'sqlite' ? <<<SQLITE
		$timeSql('$timeFormat', $exjson($input, '$.timeValue'))
		SQLITE : <<<MYSQL
		$timeSql($exjson($input, '$.timeValue'), '$timeFormat')
		MYSQL;
		};

		// dd($timeValueRaw("page_contents.`value`"));
		/**
		 * Cách này là tối ưu nhất cho việc lọc, sắp xếp cho các bài viết có kiểu: timeline
		 * Cách số 2 thì phải qua 2 câu truy vấn, không tối ưu bằng chỉ 1 câu truy vấn như cách 1
		 * Cách số 3 Hiện đang bị lỗi về cách sắp xếp theo giá trị timeValue của content.
		 * 
		 * ---> Lưu ý lỗi groupBy với chế dộ: ONLY_FULL_GROUP_BY trong Mysql 5.8.* trở lên(theo đó nó sẽ chỉ cho nhóm các tham số được tổng hợp như:
		 * Chế độ của MySQL ONLY_FULL_GROUP_BYyêu cầu bất kỳ cột nào được chọn trong câu SELECT lệnh mà không phải là một phần của GROUP BY mệnh đề 
		 * phải là một hàm tổng hợp (ví dụ: SUM(), COUNT(), MAX(), MIN(), AVG()) hoặc phụ thuộc về mặt chức năng vào GROUP BY các cột 
		 * (nghĩa là giá trị của nó được xác định duy nhất bởi GROUP BYcác cột). 
		 * Khi bạn chọn các cột không đáp ứng các tiêu chí này, MySQL sẽ báo lỗi.
		 * Sửa bằng cách tắt chế độ: "strict" trong: "config database" <---
		 */
		return $this->page->join(
			"page_contents", 		// table
			"pages.id",      		// khóa chính bảng 1
			"=",			 		// phương thức so sánh
			"page_contents.page_id" // khóa phụ bảng 2
		)->selectRaw("DISTINCT pages.*, $exjson( page_contents.`value`, '$.timeValue') AS timeValue")
			->where("page_contents.type", "=", $type)
			->whereRaw($timeValueRaw("page_contents.`value`") . " > $now")
			->orderBy('timeValue', 'ASC')
			->limit($limit)
			->groupBy('page_contents.page_id')
			->with(['pageContents' => function ($query) use ($type, $now, $timeValueRaw) {
				$query->where(PageContentInterface::TYPE, $type)
					->whereRaw($timeValueRaw('value') . " > $now")
					->orderByRaw($timeValueRaw('value') . " ASC"); // sắp xếp thứ tự tăng dần luôn.
			}])
			->get()
			->sortBy(function ($page) {
				/**
				 * Sắp xếp lại danh sách bài viết theo: timeValue vì trong quá trình truy vấn khi nhóm có thể bị lỗi 
				 * không sắp xếp đúng theo giá trị timeValue trường hợp 2 giá trị chung 1 bài viết.
				 */
				return json_decode($page['pageContents'][0]['value'], true)['timeValue'];
			})->values();

		// https://viblo.asia/p/su-dung-order-by-relation-column-trong-laravel-E375z9yRlGW
		// return (Page::fromQuery("SELECT DISTINCT pages.id, JSON_EXTRACT(page_contents.`value`, '$.timeValue') AS timeValue 
		// from pages LEFT JOIN page_contents ON pages.id = page_contents.page_id 
		// WHERE page_contents.`type`='timeline' AND DATE_FORMAT(JSON_EXTRACT(page_contents.`value`, '$.timeValue'), '%Y-%m-%d %h:%i:%s') > NOW()
		// ORDER BY timeValue
		// LIMIT 6")->toQuery()->get()->toArray());
		// ->with(['pageContents' => function ($query) use ($type) {
		// 	$query->where(PageContentInterface::TYPE, $type)
		// 		->whereRaw("DATE_FORMAT(JSON_EXTRACT(value, '$.timeValue'), '%Y-%m-%d %h:%i:%s') > NOW()");
		// }])->get()->toArray());

		/**
		 * C2:
		 * Tìm kiếm danh sách bài viết có giá trị timeValue, và timeValue lớn hơn hiện tại
		 * Sau đó sắp xếp danh sách bài viết theo giá trị tăng dần của: timeValue
		 */
		$listPageIds = array_column(DB::select(
			"SELECT DISTINCT pages.id, JSON_EXTRACT(page_contents.`value`, '$.timeValue') AS timeValue 
		from pages LEFT JOIN page_contents ON pages.id = page_contents.page_id 
		WHERE page_contents.`type`='timeline' AND DATE_FORMAT(JSON_EXTRACT(page_contents.`value`, '$.timeValue'), '%Y-%m-%d %h:%i:%s') > NOW()
		GROUP BY page_id
		ORDER BY timeValue ASC
		LIMIT $limit"
		), 'id');

		/**
		 * select pages in the search list and sort by raw  FIND_IN_SET for not auto sort by id integer
		 * Cái khó là cần sắp xếp danh sách tìm kiếm theo thứ tự id không trật tự mặc định.
		 * Dùng FIND_IN_SET để sắp xếp theo tham số truyền vào mà không bị tự động theo id tăng dần. 
		 */
		return $this->page::whereIn('id', $listPageIds)
			->orderByRaw("FIND_IN_SET(id, ?)", [implode(',', $listPageIds)])
			->with(['pageContents' => function ($query) use ($type) {
				$query->where(PageContentInterface::TYPE, $type)
					->whereRaw("DATE_FORMAT(JSON_EXTRACT(value, '$.timeValue'), '%Y-%m-%d %h:%i:%s') > NOW()");
			}])->get();

		// C3: Lỗi không triệt để giá trị trùng lặp với page_id.
		// filter by timevalue timeline.
		// SELECT * FROM page_contents where `type` = 'timeline' AND DATE_FORMAT(JSON_EXTRACT(value, '$.timeValue'), '%Y-%m-%d') > NOW() LIMIT 100
		// // $contents = DB::table(PageContentInterface::TABLE_NAME)->raw("where `type` = 'timeline' AND DATE_FORMAT(JSON_EXTRACT(value, '$.timeValue'), '%Y-%m-%d') > NOW() LIMIT 100")->select('*')->get();
		return $this->page::whereRelation('pageContents', PageContentInterface::TYPE, $type) // Illuminate\Database\Eloquent\Builder
			->has(
				'pageContents',
				callback: function ($query) use ($type) {
					$query->where(PageContentInterface::TYPE, $type)
						->whereRaw("DATE_FORMAT(JSON_EXTRACT(value, '$.timeValue'), '%Y-%m-%d %h:%i:%s') > NOW()");
				}
			)
			->latest('created_at')
			->distinct('id')
			->limit($limit)
			->with(['pageContents' => function ($query) use ($type) {
				$query->where(PageContentInterface::TYPE, $type)
					->whereRaw("DATE_FORMAT(JSON_EXTRACT(value, '$.timeValue'), '%Y-%m-%d %h:%i:%s') > NOW()");
			}])
			->get();
	}

	/**
	 * return page has most of comment
	 * @return \App\Models\Page
	 */
	public function topComment()
	{
		/**
		 * get page has much 
		 * comments relationship with condition max appear
		 */
		$page = $this->page::with('writer')->with('source')->withCount('comments')->latest('comments_count')->first();
		return $page;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function lastestList(int $limit = 6)
	{
		return $this->page->latest(PageInterface::ID)->limit($limit)->get();
	}

	/**
	 * @param string $query
	 * @return LengthAwarePaginator
	 */
	public function search(string $query)
	{
		return $this->page->where(PageInterface::TITLE, 'like', "%$query%")->paginate(6);
	}
}
