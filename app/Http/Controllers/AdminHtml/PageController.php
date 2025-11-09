<?php

namespace App\Http\Controllers\AdminHtml;

use App\Helper\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ShareAction\DeleteAction;
use App\Http\Controllers\ShareAction\UpdateAction;
use App\Models\Api\PageApi;
use App\Models\Page;
use App\Models\Types\PageContentInterface;
use App\Models\Types\PageInterface;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * custom trait
     */
    use ImageHelper;
    use DeleteAction; // action controller delete page
    use UpdateAction; // action controller update page

    protected $request;
    protected $pageApi;
    /**
     * @var \App\Models\Page $defaultModel
     */
    protected $defaultModel;

    public function __construct(
        Request $request,
        PageApi  $pageApi,
        Page $page,
    ) {
        $this->request = $request;
        $this->pageApi = $pageApi;
        $this->defaultModel = $page;
    }

    /**
     * admin list page control.
     */
    public function list(): \Illuminate\Contracts\View\View
    {
        $this->request->merge([
            'sort' => 'desc',
            'order' => 'id',
        ]);
        $actions = [
            [
                'type' => 'edit',
                'url' => PageInterface::ROUTE_PREFIX . '/edit/',
                'label' => '',
                'icon' => 'edit',
            ],
            [
                'type' => 'delete',
                'url' => PageInterface::ROUTE_PREFIX . '/delete/',
                'label' => '',
                'icon' => 'delete',
            ],
        ];

        /**
         * nguyên tắc là truyền 2 giá trị gồm:
         * 1. danh sách khóa hiển thị;
         * 2. danh sách dữ liệu hiển thị theo khóa trên.
         * các tiêu đề đã được chuyển ngữ theo file ngôn ngữ: attr.php
         */
        return view('adminhtml.pages.pageView.list', [
            'attributes' => [
                Page::ID,
                Page::IMAGE_PATH,
                Page::TITLE,
                Page::ACTIVE,
                Page::ABOVE,
            ],
            'lists' => $this->pageApi->pagePaginate(),
            'actions' => $actions
        ]);
    }

    /**
     * form create new page.
     * @return Illuminate\Http\RedirectResponse | \Illuminate\Contracts\View\View
     */
    function create()
    {
        /**
         * create new page row in database by factory.
         */
        return view('adminhtml.pages.pageView.create', [
            'listAttributes' => $this->defaultModel->formField(),
            'defaultSupportFields' => json_encode(PageContentInterface::DEFAULT_FIELD_TYPE),
            'customFields' => json_encode($this->pageApi->customFields()),
        ]);
    }

    /**
     * action for register new page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $page = Page::factory()->create($this->defaultModel->fillData($request->toArray()));
        return redirect()->to(PageInterface::ROUTE_PREFIX)->with('message', "add success new page: " . $page->{PageInterface::TITLE});
    }

    /**
     * not run
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    function detail(int $id, Request $request)
    {
        return view('adminhtml.pages.pageView.detail', []);
    }

    /**
     * action for edit form page.
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(int $id, Request $request)
    {
        $page = $this->defaultModel->find($id);
        return view('adminhtml.pages.pageView.edit', [
            'method' => 'POST',
            'action' => url("adminhtml/page/update/" . $page->{PageInterface::ID}),
            'listAttributes' => $page->formField(),
            'defaultSupportFields' => json_encode(PageContentInterface::DEFAULT_FIELD_TYPE),
            'customFields' => json_encode($this->pageApi->customFields()),
            'contentFields' => $page->pageContents->values()->toJson(),
        ]);
    }
}
