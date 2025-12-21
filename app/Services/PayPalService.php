<?php

namespace App\Services;

use Illuminate\Http\Request;
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

class PayPalService
{
    private $ordersController;
    private $client;

    public function __construct()
    {
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
     * create order
     * @param array{amount: array{currency_code: string, value: string, breakdown: array{item_total: array{currency_code: string, value: string}}, items: array} $data
     */
    public function createOrder(array $params = [])
    {
        /**
         * setup payment request(not require and setup here)
         * $orderRequest->setPaymentSource($paymetSource);
         */

        /**
         * setup order request data.
         */
        $collect = $this->formatCollect($params);

        try {
            $response = $this->client->getOrdersController()->createOrder($collect);
            /**
             * create approve order in approve_order table in database
             */
            return $response;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * the test action request done!.
     */
    public function updateOrder(string $order_id, array $params)
    {
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
            $response = $this->client->getOrdersController()->patchOrder($collect);
            /**
             * create approve order in approve_order table in database
             */
            return $response;
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
            $item->setImageUrl($product['image_url']);
            $item->setUrl($product['url']);
            // setUrl, setSku, setCategory, setQuantity, setDescription,...
            $itemsData[] = $item;
        }
        return $itemsData;;
    }

    public function getOrder(string $orderId)
    {
        $collect = [
            'id' => $orderId
        ];
        return $apiResponse = $this->client->getOrdersController()->getOrder($collect);
    }

    /**
     * Capture Order
     * order status after checkout payment process
     */
    public function captureOrder($orderId)
    {
        $collect = [
            'id' => $orderId,
            'prefer' => 'return=minimal'
        ];
        return $this->client->getOrdersController()->captureOrder($collect);
    }

    public function approvedOrder($orderId)
    {
        $collect = [
            'id' => $orderId,
            'body' => [
                PatchBuilder::init(
                    PatchOp::ADD
                )->build()
            ]
        ];
        return $apiResponse = $this->client->getOrdersController()->patchOrder($collect);
    }

    // 2. Capture Order
    // public function captureOrder($orderId)
    // {
    //     try {
    //         $response = $this->ordersController->ordersCapture($orderId);
    //         $capture = $response->result;

    //         // TODO: Update your database with the transaction ID
    //         // $capture->id

    //         return response()->json($capture);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function formatCollect($params)
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
            'returnUrl' => route('order.success'),
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
}
