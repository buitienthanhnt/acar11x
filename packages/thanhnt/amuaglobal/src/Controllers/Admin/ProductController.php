<?php

namespace Thanhnt\Amuaglobal\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Thanhnt\Amuaglobal\Helper\FormFieldHelper;
use Thanhnt\Amuaglobal\Models\Product;
use Thanhnt\Amuaglobal\Models\Types\GalleryInterface;
use Thanhnt\Amuaglobal\Models\Types\ProductInterface;

final class ProductController extends Controller
{
	public function __construct(
		protected FormFieldHelper $formFieldHelper,
	) {}

	public function listProduct()
	{
		$products = Product::when(request('k_search'), function ($query, $search) {
			$query->whereAny(['name', 'sku'], 'like', '%' . $search . '%');
		})->paginate(6);
		return Inertia::render('Amuaglobal/Screens/Adminhtml/Product/ProductList', [
			'products' => $products,
		]);
	}

	public function detailProduct(int $id)
	{
		$product = Product::findOrFail($id);
		return Inertia::render('Amuaglobal/Screens/Adminhtml/Product/ProductDetail', [
			'product' => $product,
		]);
	}

	public function createProduct()
	{
		return Inertia::render('Amuaglobal/Screens/Adminhtml/Product/ProductCreate', [
			'formFields' => $this->formFieldHelper->formatFormFields(ProductInterface::FORM_fIELDS),
		]);
	}

	public function storeProduct(Request $request)
	{
		// dd($request->all());
		$request->validate([
			'name' => 'required',
			'sku' => 'required',
			'price' => 'required',
			'qty' => 'required|integer',
		]);

		$product = Product::create(
			$request->only(ProductInterface::FILLED_FILEDS)
		);

		$category = $request->get(ProductInterface::CATEGORY_IDS);
		/**
		 * doc: https://laravel.com/docs/9.x/eloquent-relationships
		 * @var \Illuminate\Database\Eloquent\Relations\BelongsToMany
		 * đồng bộ category
		 */
		$product->categories()->sync([$category]);
		/**
		 * doc: Upload file
		 */
		$image = $request->file(ProductInterface::IMAGE);
		if ($image) {
			$path = $image->store('products', 'public');
			$product->update([ProductInterface::IMAGE => $path]);
		}
		/**
		 * đồng bộ galleries
		 */
		$product->{ProductInterface::R_GALLERIES}()
			->whereNotIn(GalleryInterface::SOURCE_ID, $request->get(ProductInterface::R_GALLERIES))
			->delete();

		$product->{ProductInterface::R_GALLERIES}()
			->createMany(
				array_map(function ($item) {
					return [
						GalleryInterface::TYPE => GalleryInterface::TYPE_PRODUCT,
						GalleryInterface::PATH => $item,
					];
				}, $request->get(ProductInterface::R_GALLERIES))
			);

		return redirect('adminhtml/amua-product', 303)->with('success', 'Product created successfully!');
		return redirect()->back()->with('success', 'Product created successfully!');
	}
}
