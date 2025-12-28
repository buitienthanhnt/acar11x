<?php

namespace Thanhnt\Ahomeglobal\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Thanhnt\Ahomeglobal\Api\CartApi;
use Thanhnt\Ahomeglobal\Api\HomeApi;
use Thanhnt\Ahomeglobal\Api\OrderApi;
use Thanhnt\Ahomeglobal\Api\RoomApi;
use Thanhnt\Ahomeglobal\Models\Types\OrderInterface;
use Thanhnt\Ahomeglobal\Services\GoogleService;
use Thanhnt\Ahomeglobal\Services\PayPalService;
use Thanhnt\Ahomeglobal\Services\StripeService;

final class CheckoutController extends Controller
{
	public function __construct(
		protected OrderApi $orderApi,
		protected CartApi $cartApi,
		protected HomeApi $homeApi,
		protected RoomApi $roomApi,
		protected PayPalService $payPalService,
		protected StripeService $stripeService,
		protected GoogleService $googleService,
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
			case 'google':
				return $this->googleService->checkout();
			default:
				/**
				 * code for checkout lately
				 */
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
	 * checkout page
	 * @return \Inertia\Response|Redirect
	 */
	public function checkout(Request $request)
	{
		/**
		 * post action
		 */
		if ($request->isMethod('POST')) {
			if ($request->input('action') === 'customer-info') {
				/**
				 * update cart customer info
				 */
				$this->cartApi->updateCartCustomer([
					'name' => $request->input('name'),
					'email' => $request->input('email'),
					'phone' => $request->input('phone'),
				]);
			} else {
				/**
				 * add cart to session.
				 */
				if ($this->cartApi->addCart(
					[
						'dateValues' => $request->input('dateSelected'),
						'home' => $request->input('home'),
						'room' => $request->input('room'),
						'qty' => $request->input('qty', 1),
					]
				)) {
					Inertia::share('messages',  'added for order in cart');
				}
			}
		}

		$cart = $this->cartApi->getCart();
		if (empty($cart)) {
			return redirect()->back()->with('error', 'Cart is empty');
		}

		return Inertia::render('Ahomeglobal/Screens/Checkout', [
			'dateSelected' => config('ahomeglobal.mode') === 'list_date' ? $cart[OrderInterface::SELECTED_TIME] :
				[$cart[OrderInterface::DATE_FROM], $cart[OrderInterface::DATE_TO]],
			'home' => $cart[OrderInterface::HOME_ID] ? $this->homeApi->getHomeDetail($cart[OrderInterface::HOME_ID]) : null,
			'room' => $cart[OrderInterface::ROOM_ID] ? $this->roomApi->getRoomDetailNoOrders($cart[OrderInterface::ROOM_ID]) : null,
			'totalPrice' => $cart[OrderInterface::TOTAL_PRICE] ?? 0,
			'customer_info' => $cart['customer_info'] ?? null,
			'step' => $request->get('step', 'customer-info'),
		]);
	}

	/**
	 * checkout success page
	 */
	public function checkoutSuccess(Request $request)
	{
		// $this->cartApi->clearCartOrder();
		$cart = $this->cartApi->getCart();
		if (!$cart) {
			return redirect()->route('home');
		}

		/**
		 * check stripe payment success
		 */
		if ($request->get('expect_order') === $this->cartApi->getExpectOrder()) {
			Inertia::share('messages', 'thank you for order!');
			/**
			 * get checkout success info
			 * add cart to Order
			 * clear cart data
			 */
			try {
				$newOrder = $this->orderApi->saveOrderByExpect($this->cartApi->getExpectOrder());
				if ($newOrder) {
					$this->orderApi->saveOrderDetail($newOrder->id, $cart);
				}
				$this->cartApi->clearCartOrder();
				if ($request->get('PayerID')) {
					// paypal payment has PayerID(now no use)
				}
				return Inertia::render('Ahomeglobal/Screens/CheckoutSuccess', [
					'order' => $this->orderApi->getOrderDetailByIncrement($newOrder->{OrderInterface::INCREMENT_ID}),
				]);
			} catch (\Throwable $th) {
				//throw $th;
			}
		}

		/**
		 * check payment error
		 */
		return redirect()->route('home')->with('error', 'payment error!');
	}
}

// http://acar11x.dev/order-success?PayerID=F57WHNF868FF6&token=0M156964E5622770F
