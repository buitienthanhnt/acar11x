<?php

namespace App\Enums;

enum CacheEnum: string
{
	case TopPage = 'top_page';
	case FlatCategoryTree = 'flatCategoryTree';

	/**
	 * the list cache should be clear after has changed once of the pages. 
	 */
	public static function flashDependPage()
	{
		return [
			self::TopPage,
			self::FlatCategoryTree,
		];
	}

	/**
	 * the list cache should be clear after has changed once of the category. 
	 */
	public static function flashDependCategory()
	{
		return [
			self::FlatCategoryTree,
		];
	}
}
