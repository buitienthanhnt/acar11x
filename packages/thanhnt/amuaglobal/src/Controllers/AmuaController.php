<?php

namespace Thanhnt\Amuaglobal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use Thanhnt\Amuaglobal\Models\Product;
use Thanhnt\Amuaglobal\Models\Types\ProductInterface;

class AmuaController extends Controller
{
	public function __construct()
	{
		// throw new \Exception('Not implemented');
	}

	/**
	 * this request call artisan for clear cache of page 
	 */
	public function clearCache(): string
	{
		/**
		 * call artisan command line in request process
		 * clear cache for app.
		 */
		$status = Artisan::call('cache:clear');
		return 'cache clear success!';
	}

	public function listProduct()
	{
		$products = Product::when(request('k_search'), function ($query, $search) {
			$query->whereAny(['name', 'sku'], 'like', '%' . $search . '%');
		})->paginate(6);
		return Inertia::render('Amuaglobal/Screens/ProductList', [
			'products' => $products,
		]);
	}

	public function detailProduct($id)
	{
		$product = Product::findOrFail($id);
		return Inertia::render('Amuaglobal/Screens/ProductDetail', [
			'product' => $product,
		]);
	}

	public function createProduct()
	{
		return Inertia::render('Amuaglobal/Screens/ProductCreate');
	}

	public function storeProduct(Request $request)
	{
		$request->validate([
			'name' => 'required',
			'sku' => 'required',
			'price' => 'required',
			'qty' => 'required|integer',
		]);

		$product = Product::create(
			$request->only(ProductInterface::FILLED_FILEDS)
		);

		$image = $request->file('image');
		if ($image) {
			$path = $image->store('products', 'public');
			$product->update(['image_path' => $path]);
		}

		return redirect('adminhtml/product', 303)->with('success', 'Product created successfully!');
		return redirect()->back()->with('success', 'Product created successfully!');
	}
}
