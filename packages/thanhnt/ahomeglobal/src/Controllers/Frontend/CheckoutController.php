<?php

namespace Thanhnt\Ahomeglobal\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\PayPalService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Thanhnt\Ahomeglobal\Api\CartApi;
use Thanhnt\Ahomeglobal\Api\OrderApi;

final class CheckoutController extends Controller
{
	public function __construct(
		protected OrderApi $orderApi,
		protected CartApi $cartApi,
		protected PayPalService $payPalService,
		protected StripeService $stripeService,
	) {
		// throw new \Exception('Not implemented');
	}

	public function paymentOrder(Request $request)
	{
		$paymentMethod = $request->input('paymentMethod');
		$cartParams = $this->cartApi->getCart();
		if (!$cartParams) {
			return back()->with([
				'error' => 'The cart empty, can not checkout!',
			]);
		}

		switch ($paymentMethod) {
			case 'paypal':
				$apiResponse = $this->payPalService->checkout($cartParams);
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
				if ($reponse && $reponse->url) {
					/**
					 * update cart for on_payment_order, on_payment, cart_item_id to id
					 */
					$this->cartApi->updateByKey('on_payment', 'stripe');
					$this->cartApi->updateByKey('on_payment_order', [
						...($cartParams['on_payment_order'] ?? []),
						'id' => $reponse->id,
					]);
					/**
					 * redirect to stripe checkout page
					 */
					return Inertia::location($reponse->url);
				}
				break;
			default:
				break;
		}
		return back()->with('error', 'error checkout!.',);
	}

	/**
	 * 
	 */
	public function checkout()
	{
		return Inertia::render('Ahomeglobal/Screens/Checkout');
	}

	/**
	 * checkout success page
	 */
	public function checkoutSuccess(Request $request)
	{
		$cart = $this->cartApi->getCart();
		if (!$cart) {
			return redirect()->route('home');
		}

		/**
		 * check stripe payment success
		 */
		if ($request->get('expect_order') === $cart['expect_order']) {
			Inertia::share('messages', 'thank you for order!');
			/**
			 * get checkout success info
			 * add cart to Order
			 * clear cart data
			 */
			$newOrder = $this->orderApi->saveOrderByExpect($cart['expect_order']);
			if ($newOrder) {
				$orderDetail = $this->orderApi->saveOrderDetail($newOrder->id, $cart);
			}
			$this->cartApi->clearCart();
			if ($request->get('PayerID')) {
				// paypal payment has PayerID(now no use)
			}
			return Inertia::render('Ahomeglobal/Screens/CheckoutSuccess', [
				'order' => $newOrder,
				'orderDetail' => $orderDetail ?? [],
			]);
		}

		/**
		 * check paypal payment success
		 */
		return redirect()->route('home');
	}
}

// http://acar11x.dev/order-success?PayerID=F57WHNF868FF6&token=0M156964E5622770F
