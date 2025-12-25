<?php

namespace Thanhnt\Ahomeglobal\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Thanhnt\Ahomeglobal\Api\CartApi;
use Thanhnt\Ahomeglobal\Api\OrderApi;
use Thanhnt\Ahomeglobal\Services\PayPalService;
use Thanhnt\Ahomeglobal\Services\StripeService;

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
				if ($reponse) {
					/**
					 * update cart for on_payment_order, on_payment, cart_item_id to id
					 */
					$this->cartApi->updateByKey('on_payment', 'stripe');
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
				break;
		}
		return back()->with('error', 'error checkout!.',);
	}

	public function paymentOrderStripe()
	{
		/**
		 * https://gauthamvijay.medium.com/creating-one-time-payments-with-stripe-via-the-checkout-session-route-4a389ea4488e
		 * https://docs.stripe.com/sdks/stripejs-react?ui=embedded-components#checkout-provider
		 * https://docs.stripe.com/payments/accept-a-payment?platform=web&ui=embedded-components&client=react#set-up-frontend
		 * 
		 * ++++ https://docs.stripe.com/payments/quickstart-checkout-sessions?client=react
		 * 
		 */
		return [
			'clientSecret' => 'cs_test_a1dFg20y8hKVEZyl4xvHsdXaE9FHmOr53ejtxb0KVyewl21cqIwivuoVDe_secret_fidnandhYHdWcXxpYCc%2FJ2FgY2RwaXEnKSdwbEhqYWAnPydmcHZxamgneCUl'
		];


		$stripe = new \Stripe\StripeClient([
			"api_key" => env('STRIPE_SECRET'),
			"stripe_version" => "2025-12-15.clover"
		]);

		$paymentIntent = $stripe->paymentIntents->create([
			'amount' => 100,
			'currency' => 'usd',
			// In the latest version of the API, specifying the `automatic_payment_methods` parameter is optional because Stripe enables its functionality by default.
			'automatic_payment_methods' => [
				'enabled' => true,
			],
		]);
		return $output = [
			'clientSecret' => $paymentIntent->client_secret,
		];


		// $cartParams = $this->cartApi->getCart();

		// $reponse = $this->stripeService->createCartItem($cartParams, true);
		// dd($reponse->toArray());
		// https://checkout.stripe.com/c/pay/
		$token = "cs_test_a1IvQfe5KIO0rGcWpFBBawf0x3vaA40iP8bGg3MYV7HZA9H4vegO2jx7Fj#fidnandhYHdWcXxpYCc%2FJ2FgY2RwaXEnKSdkdWxOYHwnPyd1blpxYHZxWjA0VmJNTnNARl9pT0dqN1I9V0dhZ3RhZElAdFFoSWFcZnxoUWtUZn00XTxVVFR2UV1nRGlQUTJmMTRzbjVxbURTcnNodFVkNmBMUkdvUkFxVGlfX1BzbH1nNTVhUE9qaGJzbCcpJ2N3amhWYHdzYHcnP3F3cGApJ2dkZm5id2pwa2FGamlqdyc%2FJyZjY2NjY2MnKSdpZHxqcHFRfHVgJz8ndmxrYmlgWmxxYGgnKSdga2RnaWBVaWRmYG1qaWFgd3YnP3F3cGB4JSUl";
		// cs_test_a1yhj4RidEnwqe3jihjLNFKq32vlJVmfWRR3ucqZzE3P8lcDghzNAjwHVU_secret_fidnandhYHdWcXxpYCc%2FJ2FgY2RwaXEnKSdwbEhqYWAnPydmcHZxamgneCUl
		// ${cs_id}_secret_${secret}




		// $checkout_session = $stripe->checkout->sessions->create([
		// 	'ui_mode' => 'custom',
		// 	'line_items' => [[
		// 		# Provide the exact Price ID (for example, price_1234) of the product you want to sell
		// 		'price' => 'price_1SggVPECZlJBo2W8YedyHXGD',
		// 		'quantity' => 1,
		// 	]],
		// 	'mode' => 'payment',
		// 	'return_url' => route('home'),
		// ]);
		return [
			'clientSecret' => $token //$checkout_session->client_secret,
		];
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
		 * check payment error
		 */
		return redirect()->route('home')->with('error', 'payment error!');
	}
}

// http://acar11x.dev/order-success?PayerID=F57WHNF868FF6&token=0M156964E5622770F
