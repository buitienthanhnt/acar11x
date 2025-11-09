<?php

namespace App\Http\Controllers\AdminHtml;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ShareAction\DeleteAction;
use App\Http\Requests\StoreWriterRequest;
use App\Http\Requests\UpdateWriterRequest;
use App\Models\Types\WriterInterface;
use App\Models\Writer;
use Illuminate\Http\Request;

class WriterController extends Controller
{
    /**
     * custom trait.
     */
    use DeleteAction;

    protected $request;
    protected $defaultModel;

    function __construct(
        Request $request,
        Writer $writer,
    ) {
        $this->request = $request;
        $this->defaultModel = $writer;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /**
         * danh sách hiển thị các thuộc tính của bảng.
         */
        $attributes = [
            WriterInterface::ID,
            WriterInterface::NAME,
            WriterInterface::IMAGE_PATH,
            WriterInterface::ACTIVE,
            WriterInterface::ALIAS,
        ];

        /**
         * danh sách hiển thị các nút tính năng phần tử bảng.
         * áp dụng cho toàn bộ các phần tử.
         */
        $actionItems = [
            [
                'type' => 'view',
                'url' => WriterInterface::ROUTE_PREFIX . '/detail/',
                'label' => '',
                'icon' => 'preview',
            ],
            [
                'type' => 'edit',
                'url' => WriterInterface::ROUTE_PREFIX . '/edit/',
                'label' => '',
                'icon' => 'edit',
            ],
            [
                'type' => 'delete',
                'url' => WriterInterface::ROUTE_PREFIX . '/delete/',
                'label' => '',
                'icon' => 'delete',
            ],
        ];

        /**
         * áp dụng phân trang.
         * @var Illuminate\Pagination\LengthAwarePaginator $writerPagiante
         */
        $writerPagiante = Writer::paginate(8);

        /**
         * params about:
         * 1. attributes: danh sách các thuộc tính cần hiển thị,
         * 2. pages: danh sách phần tử hiển thị(có thể dùng trực tiếp cho áp dụng phân trang)
         * 3. actions: danh sách các nút hỗ trợ tính năng: xem, sửa, xóa.
         */
        return view('adminhtml.pages.writerView.list', [
            'attributes' => $attributes,
            'lists' => $writerPagiante,
            'actions' => $actionItems,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('adminhtml.pages.writerView.create', [
            'listAttributes' => $this->defaultModel->formField()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWriterRequest $request)
    {
        /**
         * @input data of writer create form submit.
         */
        Writer::factory()->create($this->defaultModel->fillData($request->toArray()))->save();
        return redirect('adminhtml/writer')->with('message', 'add new writer success!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Writer $writer)
    {
        $writer = $writer->find($id);
        return view('adminhtml.pages.writerView.detail', [
            'writer' => $writer
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id, Writer $writer)
    {
        $writer = $writer->find($id);
        return view('adminhtml.pages.writerView.edit', [
            'writer' => $writer,
            'listAttributes' => $writer->formField()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, UpdateWriterRequest $request, Writer $writer)
    {
        $writer = $writer->find($id);
        /**
         * luwu y can phai khai bao bien: protected $fillable = self::FILLED_FILEDS;
         * thif moi gan tu dong duoc.
         */
        $writer->fill($request->toArray())->save();

        return redirect()->to("adminhtml/writer/detail/$writer->id")->with('message', 'updated for the writer');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, Writer $writer)
    {
        /**
         * @var Writer $writer
         */
        $writer = $writer->find($id);
        $writer->delete();
        /**
         * trả về dạng json data.
         */
        return response()->json([
            'message' => 'deleted for item',
            'code' => 200
        ]);
    }
}
