<?php

namespace App\Models\Api;

use App\Enums\ViewSourceEnum;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Page;
use App\Models\Types\CategoryInterface;
use App\Models\Types\CommentInterface;
use App\Models\Types\PageInterface;
use App\Models\Types\ViewSourceInterface;
use App\Models\ViewSource;
use Exception;
use Illuminate\Support\Facades\Session;

final class ViewSourceApi
{
	/**
	 * add or update for viewsource 
	 * @param int @target_id
	 * @param string $type
	 * @param string $action
	 * @param string $action_type
	 * @return \App\Models\ViewSource|null
	 * @throws Exception
	 */
	public function addSource(int $target_id, string $type = PageInterface::MODEL_TYPE, string $action =  ViewSourceInterface::ACTION_ADD, string $action_type = ViewSourceInterface::TYPE_LIKE,)
	{
		switch ($type) {
			case CategoryInterface::MODEL_TYPE:
				$model = Category::find($target_id);
				break;
			case CommentInterface::MODEL_TYPE:
				$model = Comment::find($target_id);
				break;
			default:
				$model = Page::find($target_id);
				break;
		}
		/**
		 * check must has one model for next action.
		 */
		if (!$model) {
			return null;
		}

		/**
		 * get model by target_id and type
		 * @var \App\Models\ViewSource|null $source
		 */
		// $source = ViewSource::where([
		// 	[ViewSourceInterface::TARGET_ID, $target_id],
		// 	[ViewSourceInterface::TYPE, $type]
		// ])->first();

		if ($source = $model->source) {
			$value = $source?->value ?? [];
			$newValue = [
				...$value,
				$action_type => isset($value[$action_type]) ?
					($action === ViewSourceInterface::ACTION_ADD ?
						$value[$action_type] + 1 : ($action === ViewSourceInterface::ACTION_SUB ?
							($value[$action_type] > 0 ? $value[$action_type] - 1 : 0) :
							$value[$action_type]
						)
					) : 1,
			];
			/**
			 * update source if the model has data.
			 */
			$source->value = $newValue;
			$source->save();
			return $source;
		} else {
			/**
			 * create new source if model has`nt source
			 */
			$source = ViewSource::create([
				ViewSourceInterface::TARGET_ID => $target_id,
				ViewSourceInterface::TYPE => $type,
				ViewSourceInterface::VALUE => [$action_type => 1],
			]);
			return $source;
		}
	}

	/**
	 * add or update for viewsource 
	 * @param int @target_id
	 * @param string $type
	 * @param string $action
	 * @param string $action_type
	 * @return \App\Models\ViewSource|null|array
	 * @throws Exception
	 */
	public function addSourceAction(int $target_id, string $type = PageInterface::MODEL_TYPE, string $action =  ViewSourceInterface::ACTION_ADD, string $action_type = ViewSourceInterface::TYPE_LIKE,)
	{
		$key = ViewSourceEnum::pageInfoKey($type, $action_type);
		if ($currentValue = Session::get($key)) {
			if ($action === ViewSourceInterface::ACTION_ADD && !in_array($target_id, $currentValue)) {
				// add to session array: push sẽ thêm giá trị vào mảng hiện có.
				Session::push($key, $target_id);
			} elseif (($action === ViewSourceInterface::ACTION_SUB) && in_array($target_id, $currentValue)) {
				// remove from session array.
				Session::put($key, array_diff($currentValue, [$target_id]));
			} else {
				return $this->getViewSource($key);
			}
			return $this->addSource(...func_get_args());
		} elseif ($action === ViewSourceInterface::ACTION_SUB) {
			return $this->getViewSource($key);
		}
		/**
		 * add value to sesison array if empty
		 */
		Session::push($key, $target_id);
		return $this->addSource(...func_get_args());
	}

	public function getViewSource(string $key)
	{
		return Session::get($key);
	}
}
