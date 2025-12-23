<?php

/**
 * PAYPAL API SERVICE TEST
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PayPalService as PayPalSvc;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Thanhnt\Ahomeglobal\Api\CartApi;

class PayPalTestController extends Controller
{

    private $paypalSvc;
    protected $stripeService;
    protected $cartApi;

    public function __construct(
        PayPalSvc $paypalSvc,
        CartApi $cartApi,
        StripeService $stripeService,
    ) {
        $this->paypalSvc = $paypalSvc;
        $this->cartApi = $cartApi;
        $this->stripeService = $stripeService;
    }


    public function index(Request $request)
    {
        $cartParams = $this->cartApi->getCart();
        // dd($cartParams);
        $apiResponse = $this->paypalSvc->createOrder($this->cartApi->formatCartToPaypalParam($cartParams));
        if ($apiResponse->isSuccess()) {
            /**
             * @var \PaypalServerSdkLib\Models\Order $order
             */
            $order = $apiResponse->getResult();
            // dd($order);

            $links = [];
            foreach ($order->getLinks() as $value) {
                $links[] = $value->jsonSerialize();
            }

            $link = collect($links)->where('rel', 'approve')->first()['href'];
            return Inertia::location($link);
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
        } else {
            $errors = $apiResponse->getResult();
            dd($errors);
        }
    }


    /**
     * 
     */
    public function updateOrder(Request $request)
    {
        $response = $this->paypalSvc->updateOrder($request->input('order_id'), []);
        if ($response->isSuccess()) {
            $order = $response->getResult();
            dd($order);
        } else {
            $errors = $response->getResult();
            dd($errors);
        }
    }

    public function orderDetail(Request $request)
    {
        $apiResponse = $this->paypalSvc->getOrder($request->get('order_id'));
        if ($apiResponse->isSuccess()) {
            $order = $apiResponse->getResult();
            dd($order->getLinks());
        } else {
            $errors = $apiResponse->getResult();
            var_dump($errors);
        }

        // Getting more response information
        var_dump($apiResponse->getStatusCode());
        var_dump($apiResponse->getHeaders());
    }

    public function capture(Request $request)
    {
        dd($this->paypalSvc->captureOrder($request->get('order_id'))->getResult());
    }

    public function paymentList()
    {
        $limit = 10;
        $offset = 0;

        // $paymentList = $this->paypalSvc->getPaymentList($limit, $offset);

        // dd($paymentList);
    }

    public function paymentDetail($paymentId)
    {
        // $paymentDetails = $this->paypalSvc->getPaymentDetails($paymentId);

        // dd($paymentDetails);
    }

    public function payment(Request $request)
    {
        if (!Auth::user()) {
            Auth::loginUsingId(1);
        }

        /**
         * add cart for stripe product dashboard(exist in stripe manager dashboard)
         * @var \Laravel\Cashier\Checkout $response
         */
        $response = ($request->user()->checkout(['price_1SggVPECZlJBo2W8YedyHXGD'], [
            'success_url' => route('home'),
            'cancel_url' => route('home'),
        ]));
        return $response->toJson();
    }

    public function stripeCharge()  {
        return$this->stripeService->stripeCharge();
    }
}
