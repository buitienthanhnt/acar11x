<?php

namespace Thanhnt\Amuaglobal\Services;

use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use PaypalServerSdkLib\Environment;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Logging\LoggingConfigurationBuilder;
use PaypalServerSdkLib\Logging\RequestLoggingConfigurationBuilder;
use PaypalServerSdkLib\Logging\ResponseLoggingConfigurationBuilder;
use PaypalServerSdkLib\Models\CheckoutPaymentIntent;
use PaypalServerSdkLib\Models\Builders\AmountBreakdownBuilder;
use PaypalServerSdkLib\Models\Builders\AmountWithBreakdownBuilder;
use PaypalServerSdkLib\Models\Builders\ItemRequestBuilder;
use PaypalServerSdkLib\Models\Builders\MoneyBuilder;
use PaypalServerSdkLib\Models\Builders\OrderApplicationContextBuilder;
use PaypalServerSdkLib\Models\Builders\OrderRequestBuilder;
use PaypalServerSdkLib\Models\Builders\PatchBuilder;
use PaypalServerSdkLib\Models\Builders\PaymentSourceBuilder;
use PaypalServerSdkLib\Models\Builders\PaypalWalletBuilder;
use PaypalServerSdkLib\Models\Builders\PaypalWalletExperienceContextBuilder;
use PaypalServerSdkLib\Models\Builders\PurchaseUnitRequestBuilder;
use PaypalServerSdkLib\Models\PatchOp;
use Psr\Log\LogLevel;
use Thanhnt\Amuaglobal\Api\CartApi;
use Thanhnt\Amuaglobal\Api\OrderApi;

class PayPalService
{
    private $client;

    public function __construct(
        protected CartApi $cartApi,
        protected OrderApi $orderApi,
    ) {
        // Đọc các cài đặt trong file config
        $paypalConfigs = config('paypal');

        // Khởi tạo ngữ cảnh
        $this->client = PaypalServerSdkClientBuilder::init()
            ->clientCredentialsAuthCredentials(
                ClientCredentialsAuthCredentialsBuilder::init(
                    $paypalConfigs['clientId'],
                    $paypalConfigs['clientSecret'],
                )
            )
            ->environment(Environment::SANDBOX)
            ->loggingConfiguration(
                LoggingConfigurationBuilder::init()
                    ->level(LogLevel::INFO)
                    ->requestConfiguration(RequestLoggingConfigurationBuilder::init()->body(true))
                    ->responseConfiguration(ResponseLoggingConfigurationBuilder::init()->headers(true))
            )
            ->build();
    }

    /**
     * @param array{status: string, cart_type: string, currency_code: string, 
     * 	cart_item: array{id: integer, name: string, price: float, qty: integer, image_url?: string}, 
     * 	order_time: array{date_from: string, date_to: string, selected_time: string}, 
     * 	customer_info?: array{name: string, email: string, phone: string}, 
     * 	shipping_method?: array{key: string, name: string, shipping_cost: float, description?: string, id?: integer}, 
     * 	shipping_address?: array{name: string, phone: string, location: string}, 
     * 	expect_order?: string, total_price: float, on_payment_order?: array{ token?: string, id?: string,}, on_payment?: string,
     * } $cartParams
     * @return \PaypalServerSdkLib\Http\ApiResponse
     */
    public function checkout($cartParams)
    {
        // dd($cartParams);
        /**
         * has exist old order
         */
        if (isset($cartParams['on_payment']) && isset($cartParams['on_payment_order']['token'])) {
            return $this->updateOrder($cartParams['on_payment_order']['token'], $cartParams);
        }
        /**
         * action for create order
         */
        return $this->createOrder($cartParams);
    }

    /**
     * create order
     * @param array{status: string, cart_type: string, currency_code: string, 
     * 	cart_item: array{id: integer, name: string, price: float, qty: integer, image_url?: string}, 
     * 	order_time: array{date_from: string, date_to: string, selected_time: string}, 
     * 	customer_info?: array{name: string, email: string, phone: string}, 
     * 	shipping_method?: array{key: string, name: string, shipping_cost: float, description?: string, id?: integer}, 
     * 	shipping_address?: array{name: string, phone: string, location: string}, 
     * 	expect_order?: string, total_price: float, on_payment_order?: array{ token?: string, id?: string,}, on_payment?: string,
     * } $cartParams
     * @return \PaypalServerSdkLib\Http\ApiResponse
     */
    public function createOrder(array $cartParams = [])
    {

        /**
         * create expect order to compare in checkout-success page.
         */
        $expectOrder = $this->orderApi->createExpectOrderByCart($cartParams);

        /**
         * setup payment request(not require and setup here)
         * $orderRequest->setPaymentSource($paymetSource);
         */
        $params =  $this->formatCartToPaypalParam($cartParams);
        $this->cartApi->updateExpectOrder($expectOrder->id);

        /**
         * setup order request data.
         */
        $collect = $this->formatCollect($params, $expectOrder);

        try {
            $response = $this->client->getOrdersController()->createOrder($collect);
            if ($response->isSuccess()) {
                /**
                 * update cart session for payement info
                 */
                $this->cartApi->updateCartByKey('on_payment_order.token', $response->getResult()->getId());
                $this->cartApi->updateExpectOrder($expectOrder->id);
                $this->cartApi->updateCartByKey('on_payment', 'paypal');
            }

            /**
             * create approve order in approve_order table in database
             */
            return $response;
        } catch (\Exception $e) {
            $expectOrder->forceDelete();
            $this->cartApi->clearExpectOrder();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * the test action request done!.
     * @param string $order_id
     * @param array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string, unit_amount: array{currency_code: string, value: float}}, customer_info: array{name: string, email: string, phone: string}, on_payment_order: array{token: string, id: string,}, on_payment: string|null, expect_order?: string} $cartParams
     * @return \PaypalServerSdkLib\Http\ApiResponse
     */
    public function updateOrder(string $order_id, array $cartParams)
    {
        $params =  $this->formatCartToPaypalParam($cartParams);
        /**
         * setup for repalce update amount
         */
        $packItem1 = PatchBuilder::init(PatchOp::REPLACE,)->build();
        $packItem1->setPath('/purchase_units/@reference_id==\'default\'/amount');
        $packItem1->setValue($params['amount']);

        /**
         * setup for repalce update items
         */
        $packItem2 = PatchBuilder::init(PatchOp::REPLACE,)->build();
        $packItem2->setPath('/purchase_units/@reference_id==\'default\'/items');
        $packItem2->setValue($params['items']);

        $collect = [
            'id' => $order_id,
            'body' => [
                $packItem1,
                $packItem2,
            ]
        ];

        try {
            $this->client->getOrdersController()->patchOrder($collect);
            /**
             * update expect order for new cart after has update some things
             */
            $this->orderApi->updateExpectOrder($this->cartApi->getExpectOrder(), $cartParams);

            /**
             * create approve order in approve_order table in database
             */
            return $this->getOrder($order_id);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Initializes a new Order Request Builder object.
     *
     * @param string $intent
     * @param PurchaseUnitRequest[] $purchaseUnits
     * @return OrderRequest
     */
    protected function createOrderRequest($intent = CheckoutPaymentIntent::CAPTURE, $purchaseUnits,)
    {
        return OrderRequestBuilder::init(
            $intent,
            $purchaseUnits
        )->build();
    }

    /**
     * @param array{returnUrl: string, cancelUrl: string,} $context
     * @return OrderApplicationContext
     */
    protected function createOrderContext(array $context = [])
    {
        $orderAplicationContext = OrderApplicationContextBuilder::init();
        $orderAplicationContext->returnUrl($context['returnUrl']);
        $orderAplicationContext->cancelUrl($context['cancelUrl']);
        return $orderAplicationContext->build();
    }

    /**
     * @see https://developer.paypal.com/docs/api/orders/v2/#definition-purchase_unit
     * price total, price items, shipping cost
     * @param array $data
     * @return PurchaseUnitRequest
     */
    protected function createPurchaseUnit(array $data = [])
    {
        /**
         * create amount with breakdown(tổng giá: gồm giá tổng các thành phần như sản phẩm, vận chuyển, ...)
         * cái này được coi như là amount main()
         * @param string $currency ex: USD | EUR | VND, ...
         * @param string $value    ex: 1 | 8 | 10 | 20 | 30, ...
         */
        $amountWithBreakdown = AmountWithBreakdownBuilder::init(
            $data['amount']['currency_code'],
            $data['amount']['value']
        )->build();

        /**
         * create amount breakdown()(bao gồm khai báo các thành phần như sản phẩm, vận chuyển, ...)
         * setShipping, setSubtotal, setTax,...
         * @see https://developer.paypal.com/docs/api/orders/v2/#definition-amount_breakdown
         * @param string $currency ex: USD | EUR | VND, ...
         * @param string $value    ex: 1 | 8 | 10 | 20 | 30, ...
         */
        $amountBreakdown = AmountBreakdownBuilder::init()->build();

        /**
         * define item total(giá sản phẩm)
         * setItemTotal()
         */
        $itemTotal = $data['amount']['breakdown']['item_total'];
        $itemTotalMoney = MoneyBuilder::init( // PaypalServerSdkLib\Models\Money
            $itemTotal['currency_code'],
            $itemTotal['value']
        )->build();
        $amountBreakdown->setItemTotal($itemTotalMoney);
        /**
         * set shipping for amount breakdown
         */
        $shipping = $data['amount']['breakdown']['shipping'];
        $amountBreakdown->setShipping(MoneyBuilder::init( // PaypalServerSdkLib\Models\Money
            $shipping['currency_code'],
            $shipping['value'],
        )->build());
        // setShipping, setSubtotal, setTax,...

        /**
         * set breakdown to amount with breakdown
         * lưu ý là giá của amount phải giá bao gồm giá tổng các thông phần như sản phẩm, vận chuyển, ...
         */
        $amountWithBreakdown->setBreakdown($amountBreakdown);

        /**
         * create purchase unit
         * đây là body content của order request.
         * nó nhận amount tổng bên trên, và khai báo danh sách các sản phẩm(item)
         */
        $purchaseUnits =  PurchaseUnitRequestBuilder::init(
            $amountWithBreakdown
        )->build();
        $purchaseUnits->setItems($this->formatItems($data['items'], $data));

        return $purchaseUnits;
    }

    protected function createPaymentSource()
    {
        $paypalExp = PaypalWalletExperienceContextBuilder::init();
        $paypalExp->returnUrl('http://acar11x.dev/paypal/status');
        $paypalExp->cancelUrl('http://acar11x.dev/checkout?step=payment');
        $paypalWallet = PaypalWalletBuilder::init()->build();
        $paypalWallet->setExperienceContext($paypalExp->build());
        $paymetSource = PaymentSourceBuilder::init()->build();
        $paymetSource->setPaypal($paypalWallet);
        return $paymetSource;
    }

    /**
     * format for items
     * @return \PaypalServerSdkLib\Models\ItemRequest[]
     */
    protected function formatItems($products, $data)
    {
        $itemsData = [];
        foreach ($products as $product) {
            /**
             * define product item info.
             * @see https://developer.paypal.com/docs/api/orders/v2/#definition-item
             * @param string $name ten
             * @param Money $amount giá
             * @param int $quantity so luong
             */
            $item = ItemRequestBuilder::init(
                $product['name'],
                MoneyBuilder::init($data['amount']['currency_code'], $product['unit_amount']['value'],)->build(), // PaypalServerSdkLib\Models\Money
                $product['quantity'],
            )->build();
            // $item->setImageUrl($product['image_url']); // must be https://
            $item->setImageUrl('https://www.amuaglobal.icu/storage/files/upload/nguoi-sinh-thang-am-lich-nay-co-the-xoay-chuyen-cuoc-doi-thanh-dat-nhu-y-hinh-2.jpg');
            $item->setUrl($product['url']);
            // setUrl, setSku, setCategory, setQuantity, setDescription,...
            $itemsData[] = $item;
        }
        return $itemsData;;
    }

    /**
     * getOrder data.
     * @param string $orderId
     * @return \PaypalServerSdkLib\Http\ApiResponse
     */
    public function getOrder(string $orderId)
    {
        $collect = [
            'id' => $orderId
        ];
        return $this->client->getOrdersController()->getOrder($collect);
    }

    /**
     * @param array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string, unit_amount: array{currency_code: string, value: float}}, customer_info: array{name: string, email: string, phone: string}, on_payment_order: array{token: string, id: string,}, on_payment: string|null, expect_order?: string} $params
     * @param ExpectOrder|null $expectOrder
     * @return array
     */
    public function formatCollect($params, $expectOrder = null)
    {
        // https://developer.paypal.com/serversdk/php/api-endpoints/orders/create-order
        // test paypal button
        /**
         * create purchase unit
         * đây là nơi chứa thông tin của order và sử dụng cho order request
         * bao gồm giá tổng(total price), discount, shipping, list item, v…
         */
        $purchaseUnits = $this->createPurchaseUnit($params);

        /**
         * init main order request for paypal
         * nhận 2 tham số là type và purchaseUnits(main data of order)
         */
        $orderRequest = $this->createOrderRequest(
            CheckoutPaymentIntent::CAPTURE,
            [$purchaseUnits]
        );

        /**
         * set order context.
         */
        $orderRequest->setApplicationContext($this->createOrderContext([
            'returnUrl' => $expectOrder ? route('checkout.success', ['expect_order' => $this->cartApi->getExpectOrder(), 'type' => 'paypal'])
                : route('checkout.success', ['type' => 'paypal']),
            'cancelUrl' => route('checkout', ['step' => 'payment']),
        ]));

        /**
         * setup payment request(not require and setup here)
         * $orderRequest->setPaymentSource($paymetSource);
         */

        /**
         * setup order request data.
         */
        $collect = [
            'body' => $orderRequest,
            'prefer' => 'return=minimal'
        ];
        return $collect;
    }

    /**
     * @param array{status: string, cart_type: string, currency_code: string, 
     * 	cart_item: array{id: integer, name: string, price: float, qty: integer, image_url?: string, description?: string, url: string}, 
     * 	order_time: array{date_from: string, date_to: string, selected_time: array[string]}, 
     * 	customer_info?: array{name: string, email: string, phone: string}, 
     * 	shipping_method?: array{key: string, name: string, shipping_cost: float, description?: string, id?: integer}, 
     * 	shipping_address?: array{name: string, phone: string, location: string}, 
     * 	expect_order?: string, total_price: float, on_payment_order?: array{ token?: string, id?: string,}, on_payment?: string,
     * } $params
     * @return array{amount: array{currency_code: string, value: float, breakdown: array{item_total: array{currency_code: string, value: float,}}}, items: array{}}
     */
    public function formatCartToPaypalParam(array $params)
    {
        $item = $params['cart_item'];
        $quantity = count($params['order_time']['selected_time']) * $params['cart_item']['qty'];
        $data = [
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => 'USD' ?: $params['currency_code'],
                        "value" => number_format($item['price'] / config('amuaglobal.exchange_vnd'), 2) * $quantity +  number_format($params['shipping_method']['shipping_cost'] / config('amuaglobal.exchange_vnd'), 2),
                        "breakdown" =>  [
                            "item_total" =>  [
                                "currency_code" => 'USD' ?: $params['currency_code'],
                                "value" => number_format($item['price'] / config('amuaglobal.exchange_vnd'), 2) * $quantity,
                            ],
                            "shipping" => [
                                "currency_code" => 'USD' ?: $params['currency_code'],
                                "value" =>  number_format($params['shipping_method']['shipping_cost'] / config('amuaglobal.exchange_vnd'), 2),
                            ]
                        ]
                    ],
                    "items" => [
                        [
                            "name" => $item['name'],
                            "description" => $item['description'] ?? null,
                            "unit_amount" => [ // require_field
                                "currency_code" => 'USD' ?: $params['currency_code'],
                                "value" => number_format($item['price'] / config('amuaglobal.exchange_vnd'), 2),
                            ],
                            "quantity" => $quantity,
                            "image_url" => $item['image_url'],
                            "url" => $item['url'],
                        ],
                    ]
                ]
            ]
        ];
        return $data['purchase_units'][0];
    }
}
