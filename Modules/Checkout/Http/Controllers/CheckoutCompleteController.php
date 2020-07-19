<?php

namespace Modules\Checkout\Http\Controllers;

use Exception;
use Modules\Order\Entities\Order;
use Modules\Payment\Facades\Gateway;
use Modules\Checkout\Events\OrderPlaced;
use Modules\Checkout\Services\OrderService;

use Illuminate\Http\Request;
use Modules\Checkout\Library\SslCommerz\SslCommerzNotification;;


class CheckoutCompleteController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param int $orderId
     * @param \Modules\Checkout\Services\OrderService $orderService
     * @return \Illuminate\Http\Response
     */

    public function store($orderId, OrderService $orderService)
    {
        
        $order = Order::findOrFail($orderId);
        
        $gateway = Gateway::get(request('paymentMethod'));

        try {
            $response = $gateway->complete($order);
        } catch (Exception $e) {
            $orderService->delete($order);

            return response()->json([
                'message' => $e->getMessage(),
            ], 403);
        }

        $order->storeTransaction($response);

        event(new OrderPlaced($order));

        session()->put('placed_order', $order);

        if (! request()->ajax()) {
            return redirect()->route('checkout.complete.show');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $order = session('placed_order');
      
    

        if( $order['payment_method'] == "Cash On Delivery"){
            //default cade
            if (is_null($order)) {
                return redirect()->route('home');
            }

            return view('public.checkout.complete.show', compact('order'));
        }

        else{

            $post_data = array();
            $post_data['total_amount'] = $order['total']; # You cant not pay less than 10
            $post_data['currency'] = "BDT";
            $post_data['tran_id'] =$order['id']; // tran_id must be unique



            # CUSTOMER INFORMATION
            $post_data['cus_name'] = $order['customer_first_name'].' '.$order['customer_last_name'];
            $post_data['cus_email'] = $order['customer_email'];
            $post_data['cus_add1'] = $order['billing_address_1'];
            $post_data['cus_add2'] = $order['billing_address_2'];
            $post_data['cus_city'] = $order['billing_city'];
            $post_data['cus_state'] = $order['billing_state'];
            $post_data['cus_postcode'] = $order['billing_zip'];
            $post_data['cus_country'] = $order['billing_country'];
            $post_data['cus_phone'] = $order['customer_phone'];
            $post_data['cus_fax'] = "";

            # SHIPMENT INFORMATION
            $post_data['ship_name'] = $order['shipping_first_name'].' '.$order['shipping_last_name'];
            $post_data['ship_add1'] = $order['shipping_address_1'];
            $post_data['ship_add2'] = $order['shipping_address_2'];
            $post_data['ship_city'] = $order['shipping_city'];
            $post_data['ship_state'] = $order['shipping_state'];
            $post_data['ship_postcode'] = $order['shipping_zip'];
            $post_data['ship_phone'] = $order['customer_phone'];
            $post_data['ship_country'] = $order['shipping_country'];
            $post_data['shipping_method'] = $order['shipping_method'];

            $post_data['product_name'] = "none";
            $post_data['product_category'] = "none";
            $post_data['product_profile'] = "none";
            

            # OPTIONAL PARAMETERS
            $post_data['value_a'] = "ref001";
            $post_data['value_b'] = "ref002";
            $post_data['value_c'] = "ref003";
            $post_data['value_d'] = "ref004";

            $sslc = new SslCommerzNotification();
            # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
            $payment_options = $sslc->makePayment($post_data, 'hosted');

            if (!is_array($payment_options)) {
                // print_r($payment_options);
                $payment_options = array();
            }
        }



    }




    

}
