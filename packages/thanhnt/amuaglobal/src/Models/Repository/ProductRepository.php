<?php

namespace Thanhnt\Amuaglobal\Models\Repository;

use Thanhnt\Amuaglobal\Models\Product;
use Thanhnt\Amuaglobal\Models\Types\ProductInterface;

final class ProductRepository
{

	public function __construct(
		protected Product $product,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * @param int $id
	 * @return \Thanhnt\Amuaglobal\Models\Product
	 */
	public function productDetail(int $id)
	{
		$product = $this->product
			->with(ProductInterface::R_CATEGORIES)
			->with(ProductInterface::R_GALLERIES)
			->findOrFail($id);

		/**
		 * doc: https://laravel.com/docs/9.x/eloquent-relationships
		 * hidden pivot attribute
		 * Mặc định sẽ trả về giá trị pivot trong liên kết tuy nhiên giá trị này không nên được hiển thị
		 */
		$product->{ProductInterface::R_CATEGORIES}->makeHidden('pivot');
		return $product;
	}
}
