<?php

namespace App\Enums;

use App\Models\Types\CategoryInterface;
use App\Models\Types\CommentInterface;
use App\Models\Types\PageInterface;

enum ViewSourceEnum: string
{
	case ACTION = 'action';           // [ViewSourceInterface::ACTION_ADD, ViewSourceInterface::ACTION_SUB]
	case ACTION_TYPE = 'action_type'; // [ViewSourceInterface::TYPE_LIKE, ViewSourceInterface::TYPE_HEART, ViewSourceInterface::TYPE_FIRE]
	case PAGE_INFO_KEY = 'page_info_';
	case CATEGORY_INFO_KEY = 'cate_info_';
	case COMMENT_INFO_KEY = 'comment_info_';

	public static function pageInfoKey(string $type, string $action_type): string
	{
		return match ($type) {
			PageInterface::MODEL_TYPE => self::PAGE_INFO_KEY->value . $action_type,
			CategoryInterface::MODEL_TYPE => self::CATEGORY_INFO_KEY->value . $action_type,
			CommentInterface::MODEL_TYPE => self::COMMENT_INFO_KEY->value . $action_type,
		};
	}
}
