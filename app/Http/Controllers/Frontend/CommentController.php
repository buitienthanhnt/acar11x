<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Api\CommentApi;
use App\Models\Comment;
use App\Models\Types\CommentInterface;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $defaultModel;
    protected $commentApi;

    function __construct(
        Comment $comment,
        CommentApi $commentApi,
    ) {
        $this->defaultModel = $comment;
        $this->commentApi = $commentApi;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->commentApi->commentPagination($request->get('limit', 8));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        if (!$user = $request->user()) {
            return response()->json([
                'message' => 'error response for request not authenticate',
            ], 401);
        }

        try {
            /**
             * insert for new comment.
             * dùng hàm fill thì có thể gán hàng loạt kể cả có các thuộc tính thừa.
             * nếu dùng qua factory->create() thì gán hàng loạt phải đúng các thuộc tính cần, nếu gán hàng loạt có thuộc tính thừa sẽ bị lỗi. 
             */
            $this->defaultModel->fill([...$request->toArray(), CommentInterface::USER_ID => $user->id])->save();
            /**
             * return for success.
             */
            return [
                'message' => 'response for axios success!!',
            ];
        } catch (\Throwable $th) {
            /**
             * return json data for request
             * response code = 5xx is error of server
             * https://www.youtube.com/watch?v=itjTPLojmvA
             * https://viblo.asia/p/exceptions-trong-laravel-lam-the-nao-de-catch-handle-va-tu-tao-mot-exception-de-xu-ly-van-de-cua-rieng-minh-bJzKmGnOl9N
             * https://laravel.com/docs/12.x/errors
             */
            throw $th;
            return response()->json([
                'message' => 'error response for axios',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
