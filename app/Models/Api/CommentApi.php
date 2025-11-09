<?php

namespace App\Models\Api;

use App\Models\Comment;
use App\Models\Types\CommentInterface;
use App\Models\Types\PageInterface;
use Illuminate\Http\Request;

final class CommentApi implements CommentInterface
{
	protected $request;
	protected $comment;

	public function __construct(
		Request $request,
		Comment $comment,
	) {
		$this->request = $request;
		$this->comment = $comment;
	}

	/**
	 * @param int $limit
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	function commentPagination(int $limit = 12)
	{
		$id = $this->request->get('targetId');
		$type = $this->request->get('type', PageInterface::MODEL_TYPE);
		$parent_id = $this->request->get(CommentInterface::PARENT_ID);

		return $this->comment->where(CommentInterface::TYPE, '=', $type)
			->where(CommentInterface::PARENT_ID, '=', $parent_id)
			->where(CommentInterface::TARGET_ID, '=', $id)
			->with('user')->with('source')
			->paginate($limit);
	}
}
