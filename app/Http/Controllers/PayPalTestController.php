<?php

/**
 * PAYPAL API SERVICE TEST
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PayPalService as PayPalSvc;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Thanhnt\Ahomeglobal\Api\CartApi;

class PayPalTestController extends Controller
{

    private $paypalSvc;
    protected $cartApi;

    public function __construct(
        PayPalSvc $paypalSvc,
        CartApi $cartApi,
    ) {
        $this->paypalSvc = $paypalSvc;
        $this->cartApi = $cartApi;
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

    public function status()
    {
        return 'success order request';
    }

    public function orderDetail(Request $request)
    {
        $apiResponse = $this->paypalSvc->getOrder($request->get('order_id'));
        if ($apiResponse->isSuccess()) {
            $order = $apiResponse->getResult();
            dd($order);
        } else {
            $errors = $apiResponse->getResult();
            var_dump($errors);
        }

        // Getting more response information
        var_dump($apiResponse->getStatusCode());
        var_dump($apiResponse->getHeaders());
    }

    protected function approved(Request $request)
    {
        dd($this->paypalSvc->approvedOrder($request->get('order_id'))->getResult());
    }

    public function capture(Request $request)
    {
        dd($this->paypalSvc->captureOrder($request->get('order_id'))->getResult());
    }

    public function cancel(Request $request)
    {
        return 'cancel paypal';
        // return $this->paypalSvc->captureOrder($request->get('order_id'));
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
}
