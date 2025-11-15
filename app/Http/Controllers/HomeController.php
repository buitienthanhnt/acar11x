<?php

namespace App\Http\Controllers;

use App\Enums\DesignEnum;
use App\Enums\ViewSourceEnum;
use App\Events\ViewCount;
use App\Helper\ImageHelper;
use App\Http\Controllers\ShareAction\LoadContructLayout;
use App\Http\Resources\PaginateData;
use App\Models\Api\CategoryApi;
use App\Models\Api\PageApi;
use App\Models\Api\ViewSourceApi;
use App\Models\Api\WriterApi;
use App\Models\Category;
use App\Models\Design;
use App\Models\Types\CategoryInterface;
use App\Models\Types\DesignInterface;
use App\Models\Types\PageInterface;
use App\Models\Types\ViewSourceInterface;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    use LoadContructLayout;
    use ImageHelper;

    /**
     * @var \Illuminate\Http\Request $request
     */
    protected $request;

    /**
     * @var \App\Models\Api\PageApi $pageApi
     */
    protected $pageApi;
    protected $writerApi;
    protected $viewSourceApi;
    protected $categoryApi;

    protected $design;

    public function __construct(
        Request $request,
        PageApi $pageApi,
        WriterApi $writerApi,
        ViewSourceApi $viewSourceApi,
        CategoryApi $categoryApi,
        Design $design,
    ) {
        $this->request = $request;
        $this->pageApi = $pageApi;
        $this->writerApi = $writerApi;
        $this->viewSourceApi = $viewSourceApi;
        $this->categoryApi = $categoryApi;
        $this->design = $design;
    }

    public function home()
    {
        // $banner = Page::latest()->first();
        $designConstruct = $this->design->select(DesignInterface::VALUE, DesignInterface::NAME)->where(DesignInterface::TYPE, 'home-page')->get();
        Inertia::share('components', $designConstruct,);
        /**
         * load for layout construct page.
         */
        $this->loadLayout($designConstruct);

        /**
         * hàm share sẽ lưu trữ các giá trị vào các tham số cho hàm render.
         * các tính năng như bất đồng bộ(optional), gom nhóm(defer).
         * optional: tải sau(bất đồng bộ dùng với: <WhenVisible> (phải dùng để thành phần mới gọi dữ liệu api bất đồng bộ)) 
         *           sẽ gọi khi phần tử được hiển thị trong khung nhìn(async)
         */
        // Inertia::share('timeLine', inertia()->optional(fn() => $this->pageApi->pageFilterTimeline('timeline')));

        /**
         * defer: gom nhóm để tải dữ liệu sau(không tải cùng với hàm: render chính)
         * dùng với: <Deferred>(nên dùng(không bắt buộc) để thành phần sử dữ liệu api bất đồng bộ và kiểm soát trạng thái).
         * có thể nhóm các dữ liệu với dùng WhenVisible thì khá hữu ích theo đó thì dữ liệu sẽ luôn tải theo nhóm ban đầu còn khi nào: WhenVisible
         * thì nó sẽ dùng giá trị đó luôn mà không phải gọi lại; nó khác với WhenVisible là optional chỉ gọi api khi nó hiển thị
         * còn: defer sẽ luôn gọi từ đầu.
         */
        // Inertia::share('testDef', inertia()->defer(fn() => $this->pageApi->pageRandom(6)));

        /**
         * Inertia info:
         * vendor/inertiajs/inertia-laravel/src/Inertia.php
         * vendor/inertiajs/inertia-laravel/src/ResponseFactory.php
         */
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'demo' => [],
            // 'videos' => inertia()->optional(function () { // load sau: sẽ gọi khi phần tử được hiển thị trong khung nhìn(async)
            //     return Page::whereRelation('pageContents', 'type', 'video')->latest('created_at')->distinct('id')->limit(2)->with(['pageContents' => function ($query) {
            //         $query->where('type', 'video');
            //     }])->get();
            // }),
            // 'banner' => $banner,
            // 'swipeList' => inertia()->defer(function () { // swipeList and testDef will be loaded in one api group
            //     return $this->pageApi->pageRandom(6);
            // }, 'async'),
        ])->withViewData(['meta' =>  "adoc global app design by thant"]);
    }

    /**
     * detail of paper
     */
    public function detail(string $alias, Request $request)
    {
        /**
         * get page by alias(first of paper by alias)
         * inject with writer model, page content, tags data, category value in list value
         * done!
         */
        $pageDetail = $this->pageApi->detailByAttr(PageInterface::ALIAS, $alias);
        /**
         * dispatch event for count of page view.
         */
        ViewCount::dispatch($pageDetail);

        /**
         * load data layout for page components
         */
        $this->loadLayout([
            [
                'name' => 'centerCategory',
                'type' => DesignEnum::CenterCategory->value,
            ],
        ]);
        /**
         * render page view.
         */
        return Inertia::render('Screen/PageScreen/Detail', [
            'page' => $pageDetail,
        ]);
    }

    /**
     * list of all paper.
     */
    public function list(Request $request)
    {
        return Inertia::render('Screen/PageScreen/List', [
            'paginate' => function () {
                $pages = $this->pageApi->pageFilterPaginate(6);
                return $pages ? new PaginateData($pages) : [];
            },
            'filters' => $this->pageApi->pageFilters(),
        ]);
    }

    /**
     * list render of writer.
     */
    public function account()
    {
        /**
         * list of writer pagination
         * @var \Illuminate\Pagination\LengthAwarePaginator $writers
         */
        $writers = $this->writerApi->writerPagination(6);
        return Inertia::render('Screen/Writer/WriterList', $writers);
    }

    /**
     * controller for detail of writer and list page of writer.
     */
    public function writerDetail(int $id)
    {
        $writer = $this->writerApi->getById($id);
        $pages = $writer->pages()->with('source')->withCount('comments')->paginate(6);
        return Inertia::render('Screen/Writer/WriterDetail', [
            'writer' => $writer,
            'pages' => $pages ? new PaginateData($pages) : [],
        ]);
    }

    /**
     * list of categories
     */
    public function docs(): Response
    {
        $allCategory = Category::all();
        return Inertia::render('Screen/CategoryScreen/Docs', [
            'categories' => $allCategory
        ]);
    }

    /**
     * detail for category and list page of category
     */
    public function category(string $category = '')
    {
        $categoryByAlias = Category::where(CategoryInterface::ALIAS, $category)->first();

        return Inertia::render('Screen/PageScreen/PagesByCategory', [
            "category" => $categoryByAlias,
            'pages' => new PaginateData($categoryByAlias->pages()->paginate(6))
        ]);
    }

    /**
     *return list of all pages sort by newest.
     */
    public function about()
    {
        /**
         * set default sort for list page by merge request parameter
         */
        $this->request->merge([
            'sort' => 'desc',
            'order' => 'id',
        ]);

        /**
         * render about page with paginate data format value by resourceClass.
         */
        return Inertia::render('About', [
            "pages" => Inertia::scroll(fn() => new PaginateData($this->pageApi->pageFilterPaginate(6))),
        ]);
    }

    /**
     * test for register url with security, url with time live
     */
    function Signature(Request $request): void
    {
        /**
         * check authenticate for request.
         */
        if (!$request->hasValidSignature()) {
            abort('403');
        };

        /**
         * create link with authenticate and live time.
         */
        // $link = \Linkeys\UrlSigner\Facade\UrlSigner::generate(action([HomeController::class, 'list']), ['id' => 1], '+1 hours', 1);
        // echo $link->getFullUrl();
        // return Inertia::render('Detail', [
        //     "value" => 123,
        //     "once_link" =>  '/' // $link->getFullUrl()
        // ]);
    }

    /**
     * function for render page list of tag.
     */
    public function tag($value, Request $request)
    {
        return Inertia::render('Screen/PageScreen/PageByTag', [
            'tag' => $value,
            'pages' => $this->pageApi->pageByTag($value)
        ]);
    }

    /**
     * api for add view source info.
     */
    public function addSource(Request $request)
    {
        $request->validate([
            ViewSourceInterface::TARGET_ID => ['required', 'integer'],
        ]);

        return $this->viewSourceApi->addSourceAction(
            target_id: $request->get(ViewSourceInterface::TARGET_ID),
            action_type: $request->get(ViewSourceEnum::ACTION_TYPE->value, ViewSourceInterface::TYPE_LIKE),
            action: $request->get(ViewSourceEnum::ACTION->value, ViewSourceInterface::ACTION_ADD),
            type: $request->get(ViewSourceInterface::TYPE, PageInterface::MODEL_TYPE),
        );
    }

    function langSetup(Request $request)
    {
        // sleep(2);
        // dd($request->all());
        // return to_route('home');
        // $this->uploadImages($request->file('avatar'), 'demoUpfile');
        $fileName = time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $fileName);

        return redirect()->back();
    }

    /**
     * layouts: [
     * 'com-1' => data,
     * 'com-2' => data,
     * 'com-3' => data,
     * ]
     * server:
     * nếu dùng optional: Inertia::share('com-1', inertia()->optional(fn() => data)); để đăng ký.
     * 
     * client:
     * const key = 'com-1';
     * const { props} = usePage();
     * <WhenVisible data="com-1" fallback={() => <ListSke></ListSke>}>
     *      {props[key] && <DupVideos items={props[key]}></DupVideos>}
     */
}
