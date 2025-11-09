<?php

namespace App\Models\Api;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

final class CategoryApi
{

	protected $category;

	public function __construct(
		Category $category
	) {
		$this->category = $category;
	}

	/**
	 * @param int $limit
	 * @return LengthAwarePaginator
	 */
	function paginate(int $limit = 12)
	{
		return $this->category->paginate($limit);
	}

	/**
	 * 
	 */
	public function centerCategories()
	{
		/**
		 * Illuminate\Database\Eloquent\Model -> on->
		 * Illuminate\Database\Eloquent\Builder ->
		 * Illuminate\Database\Eloquent\Concerns\QueriesRelationships -> whereHas
		 */
		// return $this->category::has('pages')->with('pages', fn($query) => $query->take(4))->get(); // not random page
		return $this->category::has('pages', callback: function($query) {$query->select(['id']);}) // has pages not null(the optimate of query.)
			->inRandomOrder()
			->take(4)
			->get()
			->map(
				function ($category) {
					/**
					 * set limit for category page item max 6
					 * https://stackoverflow.com/questions/43097559/laravel-eloquent-limit-results-for-relationship
					 */
					$category->setRelation('pages', $category->pages()->latest('id')->take(6)->get());
					return $category;
				}
			);

		// return $this->category::has('pages',)->with('pages',)->get()->map( // the query not optimate than top.
			// 	function ($category) {
			// 		$category->setRelation('pages', $category->pages()->latest('id')->take(6)->get());
			// 		return $category;
			// 	}
		// );
	}
}
