<?php

namespace App\Http\Controllers;

use App\Enums\ViewSourceEnum;
use App\Http\Resources\PaginateData;
use App\Models\Page;
use App\Models\Types\PageInterface;
use App\Models\Types\ViewSourceInterface;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestController extends Controller
{
    protected $viewSourceApi;
    protected $pageApi;

    public function __construct(
        \App\Models\Api\ViewSourceApi $viewSourceApi,
        \App\Models\Api\PageApi $pageApi,
    ) {
        $this->viewSourceApi = $viewSourceApi;
        $this->pageApi = $pageApi;
    }

    public function addViewSource(Request $request)
    {
        if (!$target_id = $request->get(ViewSourceInterface::TARGET_ID)) {
            throw new Exception("Error Processing Request target_id is requuired!", 1);
        }
        return $this->viewSourceApi->addSource(
            target_id: $target_id,
            action_type: $request->get(ViewSourceEnum::ACTION_TYPE->value, ViewSourceInterface::TYPE_LIKE),
            action: $request->get(ViewSourceEnum::ACTION->value, ViewSourceInterface::ACTION_ADD),
            type: $request->get(ViewSourceInterface::TYPE, PageInterface::MODEL_TYPE),
        );

        // $response = ViewSource::create($request->all());
        // dd($response->{ViewSourceInterface::VALUE});
        // return $response;
    }

    public function getViewSource($page_id)
    {
        $page = Page::with('source')->find($page_id);
        return $page;
    }

    public function homeTest()
    {
        return Inertia::render("Screen/TestScreen/Test",);
    }

    public function mergeProp()
    {
        /**
         * lưu ý các giá trị có thể gộp thì kiểu của nó phải là 1 mảng(array) hoặc 1 tập hợp(collection).
         */
        $noti = session()->get('noti');
        $postData = [
            ['id' => 12, 'value' => 'delta 121212', 'name' => 'name 12222',],
        ];
        $chatData = [
            'messages' => [
                ['id' => 2, 'text' => str()->random(2), 'user' => 'Alice'],
                ['id' => 3, 'text' => str()->random(2), 'user' => 'Bob'],
            ],
            'online' => 12,
        ];

        $pops = ['id' => 15, 'value' => 'delta 15', 'name' => 'name 15', "items" => [123]];

        /**
         * Khi hợp nhất mảng, bạn có thể sử dụng tham số để khớp các mục hiện có theo một trường cụ thể 
         * và cập nhật chúng thay vì thêm các mục mới.matchOn
         */
        Inertia::share('data', Inertia::merge($postData)->matchOn('id'));

        /**
         * Thêm giá trị mới vào thuộc tính chỉ định, ngoài ra nó sẽ thay thế các thuộc tính còn lại
         */
        Inertia::share('pops', Inertia::merge($pops)->append('items'));

        /**
         * deepMerge: dùng cho thuộc tính của dữ liệu so sánh. Theo đó dữ liệu mới sẽ thêm vào: messages
         */
        return Inertia::render("Screen/TestScreen/MergeProp", [
            "notifi" => Inertia::merge(fn() => $noti ? [$noti] : [])->prepend(), // thêm preprend là thêm vào trước giá trị cũ, bình thường nó sẽ thêm vào sau.
            'chat' => Inertia::deepMerge($chatData)->matchOn('messages.id'), // matchOn: sẽ thay thế phần tử dựa vào thuộc tính(messages.id). nếu không bằng nó sẽ gộp vào danh sách trước đó(messages)
        ]);
    }

    public function postMerge()
    {
        session()->flash('noti', str()->random(10));
        return back();
    }

    public function partialReloads()
    {

        /**
         * Theo khuyến cáo của InertiaJs thì các giá trị được gán cho khóa của tham số trả về nên được:
         * Đặt trong 1 hàm(function) để nó sẽ được đánh giá trước(có cần thực thi tính toán lại hay không)
         * Sau đó mới quyết định: thực hiện chạy hàm đó hay bỏ qua
         */
        return Inertia::render('Screen/TestScreen/PartialProp', [
            'page' => function () {
                return $this->pageApi->getRandom(4);
            },
            'demo' => function () {
                return [
                    'key' => str()->random(4),
                    'value' => str()->random(3),
                ];
            },
            'user' => Inertia::always(fn() => [ // luôn luôn được tải lại kể cả phía người dùng sử dụng only hay except như nào.
                'id' => rand(1, 100),
                'name' => str()->random(5),
            ]),
        ]);
    }

    /**
     * @return \Inertia\Response
     */
    public function remenberState(Request $request)
    {
        if ($request->has('query')) {
            $request->validate([
                'query' => ['min:3', 'max:80'],
            ]);
        }
        $query = $request->get('query');
        return Inertia::render('Screen/TestScreen/RemenberState', [
            'dataSearch' => Inertia::merge(function () use ($query) {
                return $query ? $this->pageApi->search($query) : [];
            })->append('data'),
        ]);
    }

    public function remenberPost(Request $request)
    {
        // dd($request->all());
        return back();
    }

    public function timeline() {
        return $this->pageApi->pageFilterTimeline('timeline');
    }

    /**
     * 
     */
    function paginate() {
       return (new PaginateData($this->pageApi->pagePaginate(6))); 
    }
}
