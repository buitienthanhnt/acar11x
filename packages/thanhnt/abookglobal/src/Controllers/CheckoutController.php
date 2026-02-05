<?php

namespace Thanhnt\Abookglobal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Thanhnt\Abookglobal\Api\BookApi;
use Thanhnt\Abookglobal\Api\BookOrderApi;
use Thanhnt\Amuaglobal\Models\Types\ExpectOrderInterface;
use Thanhnt\Amuaglobal\Models\Types\OrderInterface;
use Thanhnt\Amuaglobal\Api\CartApi;
use Thanhnt\Amuaglobal\Api\ExpectOrderApi;
use Thanhnt\Amuaglobal\Api\OrderApi;
use Thanhnt\Amuaglobal\Models\ExpectOrder;
use Thanhnt\Amuaglobal\Services\CheckMoneyService;
use Thanhnt\Amuaglobal\Services\PayPalService;
use Thanhnt\Amuaglobal\Services\StripeService;

final class CheckoutController extends Controller
{
	public function __construct(
		protected CartApi $cartApi,
		protected OrderApi $orderApi,
		protected BookOrderApi $bookOrderApi,
		protected BookApi $bookApi,
		protected ExpectOrderApi $expectOrderApi,
		protected PayPalService $paypalService,
		protected StripeService $stripeService,
		protected CheckMoneyService $checkMoneyService,
	) {
		// throw new \Exception('Not implemented');
	}

	public function checkout(Request $request)
	{
		/**
		 * action with post method
		 */
		if ($request->isMethod('POST')) {
			/**
			 * validate base data
			 */
			if ($request->input('action') === 'customer-info') {
				# code... update customer-info
				$this->cartApi->updateCustomerInfo([
					'name' => $request->input('name'),
					'email' => $request->input('email'),
					'phone' => $request->input('phone'),
				]);
			} else if ($request->input('action') === 'shipping-info') {
				/**
				 * update shipping info: address and method
				 */
				$cartData = $this->cartApi->getCart();
				/**
				 * update shipping address
				 */
				if ($request->input('same_as_customer')) {
					$this->cartApi->updateShippingAddress([
						'location' => $request->input('location'),
						'name' => $cartData['customer_info']['name'],
						'phone' => $cartData['customer_info']['phone'],
					]);
				} else {
					$this->cartApi->updateShippingAddress([
						'location' => $request->input('location'),
						'name' => $request->input('name'),
						'phone' => $request->input('phone'),
					]);
				}
				/**
				 * update shipping method
				 */
				$this->cartApi->updateShippingMethod($request->input('method'));
			} else {
				/**
				 * validate and
				 * add book to cart.
				 */
				Validator::make($request->all(), [
					'book_id' => 'required|integer',
					'dateSelected' => 'required|array',
					'dateSelected.*' => 'date_format:Y-m-d',
				])->validate();

				/**
				 * check available time
				 */
				if (!$this->bookOrderApi->checkAvailableTime($request->get('book_id'), $request->input('dateSelected'),)) {
					return redirect()->back()->with('error', 'Selected time is not available, please choose another time.');
				}
				$this->cartApi->addToCart($request->input('book_id'), $request->input('dateSelected'));
			}
		}

		/**
		 * check cart data
		 */
		$cart = $this->cartApi->getCart();
		if (!$cart) {
			return redirect()->back()->with('error', 'The cart empty, can not checkout!');
		}
		// dd($cart);
		return Inertia::render('Abookglobal/Screens/Checkout', [
			'cart' => $cart,
			'step' => $request->input('step', 'customer-info'),
			'shipping_method' => config('amuaglobal.shipping_method'),
		]);
	}

	/**
	 * checkout success action
	 */
	public function checkoutSuccess(Request $request)
	{
		$cartData = $this->cartApi->getCart();
		if (!$cartData) {
			return redirect()->route('home')->with('error', 'The cart empty, can not checkout!');
		}

		if ($request->get('type') === 'paypal') {
			/**
			 * array:3 [▼ // packages/thanhnt/abookglobal/src/Controllers/CheckoutController.php:111
			 * "type" => "paypal"
			 * "token" => "8W427954NJ133221H"
			 * "PayerID" => "F57WHNF868FF6"
			 * ]
			 */
			if (!$request->get('PayerID') || ($request->get('token') !== $cartData['on_payment_order']['token'])) {
				return redirect()->route('home')->with('error', 'Payment failed, can not checkout!');
			}
		}

		if ($request->get('type') === 'stripe') {
			# code... http://acar11x.dev/checkout-success?expect_order=01kgag8t0efvjye334c3dcs4z3&type=stripe
		}

		/**
		 * the request must be expect_order
		 */
		if (!$expectOrder = $request->get('expect_order')) {
			return redirect()->route('home')->with('error', 'Payment failed, can not checkout!');
		}

		/**
		 * check session cart expect order same as the request input
		 */
		if ($request->get('expect_order') !== $this->cartApi->getExpectOrder()) {
			return redirect()->route('home')->with('error', 'Payment failed, can not checkout!, order not match!');
		}

		/**
		 * clone expect order to order
		 */
		$expectOrder = $this->expectOrderApi->getExpectOrderById($request->get('expect_order'));

		Inertia::share('messages', 'Cảm ơn quý khách đã đặt lịch!');
		/**
		 * cretae order after payment success and not create order now if payment type is checkmoney
		 */
		if (in_array($request->get('type'), ['paypal', 'stripe'])) {
			$expectOrder->{ExpectOrderInterface::STATUS} = 'success';
			$expectOrder->save();
			$order =  $this->orderApi->getOrderByIncrement(
				$this->orderApi->cloneExpectOrderToOrder($expectOrder->refresh())->{OrderInterface::INCREMENT_ID},
			);
		} else {
			$order = $expectOrder;
		}
		/**
		 * associate book(gán lại item detail cho order hoặc expectorder do mặc định nó trả về Model của product)
		 * tùy vào từng loại sản phẩm mà có sự chuyển đổi phù hợp.
		 */
		$book = $this->bookApi->getBookById($expectOrder->item_id);
		$order->item()->associate($book);
		/**
		 * clear cart data after checkout
		 */
		$this->cartApi->clearCart();

		return Inertia::render('Abookglobal/Screens/CheckoutSuccess', [
			'order' => $order,
			'shipping_method' => config('amuaglobal.shipping_method')
		]);
	}

	public function checkoutAction(Request $request)
	{
		/**
		 * update payment method into cart data
		 */
		$this->cartApi->updatePaymentMethod($request->get('paymentMethod'));
		/**
		 * @var array{status: string, cart_type: string, currency_code: string, 
		 * 	cart_item: array{id: integer, name: string, price: float, qty: integer, image_path?: string}, 
		 * 	order_time: array{date_from: string, date_to: string, selected_time: string}, 
		 * 	customer_info?: array{name: string, email: string, phone: string}, 
		 * 	shipping_method?: array{key: string, name: string, shipping_cost: float, description?: string, id?: integer}, 
		 * 	shipping_address?: array{name: string, phone: string, location: string}, 
		 * 	expect_order?: string, total_price: float, on_payment_order: array{ token?: string, id?: string,}, on_payment?: string,
		 * } $cartData
		 */
		$cartParams = $this->cartApi->getCart();

		switch ($request->get('paymentMethod')) {
			case 'paypal':
				$apiResponse = $this->paypalService->checkout($cartParams);
				if ($apiResponse->isSuccess()) {
					/**
					 * @var \PaypalServerSdkLib\Models\Order $order 0M156964E5622770F
					 */
					$order = $apiResponse->getResult();

					/**
					 * get links redirect
					 */
					$links = [];
					foreach ($order->getLinks() as $value) {
						$links[] = $value->jsonSerialize();
					}
					if ($href = collect($links)->where('rel', 'approve')->first()) {
						$link = $href['href'];
						/**
						 * redirect to paypal checkout page
						 */
						return Inertia::location($link);
					} else {
						return back()->with('error', 'error checkout,the order not active. Please clear order and reorder!',);
					}

					/**
					 * after payment success redirect to $link
					 * in there server update approved_order table to order table in database
					 * and delete approved order
					 * 
					 * if cancel redirect to cancel url and delete approved order
					 * 
					 * trong trường hợp không thanh toán sẽ xóa approved order
					 * 
					 * trong trường hợp quay lại trang checkout sẽ luôn kiểm tra cart order còn hợp lệ hay không
					 * 
					 * khi người dùng vào payment lại thì sẽ kiểm tra order token paypal còn hợp lệ hay khong, lấy order detail,
					 * nếu thỏa mãn sẽ tạo lại approved order, sau đó chuyển hướng tới url paypal.
					 * 
					 */
				}
				break;
			case 'stripe':
				$reponse = $this->stripeService->checkout($cartParams);
				/**
				 * update cart for on_payment_order, on_payment
				 */
				if ($reponse) {
					/**
					 * update cart for on_payment_order, on_payment, cart_item_id to id
					 */
					$this->cartApi->updateCartByKey('on_payment', 'stripe');
					/**
					 * return for online inpage payment
					 */
					if (config('ahomeglobal.payment.stripe.ui_mode') === 'custom') {
						return ['clientSecret' => $reponse->client_secret,];
					}
					/**
					 * redirect to stripe checkout page
					 */
					return Inertia::location($reponse->url);
				}
				break;
			default:
				/**
				 * @var ExpectOrder $responseApi
				 */
				$responseApi = $this->checkMoneyService->checkout($cartParams);
				return redirect()->route('checkout.success', ['type' => 'checkmoney', 'expect_order' => $responseApi->{ExpectOrderInterface::ID}]);
				break;
		}
	}
}
