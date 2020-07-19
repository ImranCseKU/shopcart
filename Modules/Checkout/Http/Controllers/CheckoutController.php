<?php

namespace Modules\Checkout\Http\Controllers;

use Exception;
use Modules\Support\Country;
use Modules\Cart\Facades\Cart;
use Modules\Page\Entities\Page;
use Illuminate\Routing\Controller;
use Modules\Payment\Facades\Gateway;
use Modules\User\Services\CustomerService;
use Modules\Checkout\Services\OrderService;
use Modules\Order\Http\Requests\StoreOrderRequest;

//todo
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['cart_not_empty', 'check_stock', 'check_coupon_usage_limit']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)    //todo
    {
        //todo
        $content = $request->cookie('fleetcart_session');
        $session_key = $content."_cart_items";
        // $data = $request->session()->all();
        $value = $request->session()->get($session_key);
        $json_value = json_decode($value, true);
        
        $json_value_keys = array_keys($json_value);
        
        //todo
        
        $total_order_price = 0;

        for ($i = 0 ; $i< count($json_value_keys) ; $i++){
            $price = $json_value[$json_value_keys[$i]]["price"];
            $quantity = $json_value[$json_value_keys[$i]]["quantity"];

            $total_order_price += $price * $quantity;
        }


        // $total_order_price = $price * $quantity;

        // $min_order_amount = DB::table('settings')->where({{}})

        if( $total_order_price < 1000){
            session()->flash('message', 'Minimum order should be greater then 1000 tk.');
            session()->flash('type', 'danger');
            return redirect('/');
        }

        $cart = Cart::instance();
        $countries = Country::supported();
        $gateways = Gateway::all();
        $termsPageURL = Page::urlForPage(setting('storefront_terms_page'));

        return view('public.checkout.create', compact('cart', 'countries', 'gateways', 'termsPageURL'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Modules\Order\Http\Requests\StoreOrderRequest $request
     * @param \Modules\User\Services\CustomerService $customerService
     * @param \Modules\Checkout\Services\OrderService $orderService
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request, CustomerService $customerService, OrderService $orderService)
    {
        
        if (auth()->guest() && $request->create_an_account) {
            $customerService->register($request)->login();
        }

        $order = $orderService->create($request);

        $gateway = Gateway::get($request->payment_method);

        try {
            $response = $gateway->purchase($order, $request);
        } catch (Exception $e) {
            $orderService->delete($order);

            return response()->json([
                'message' => $e->getMessage(),
            ], 403);
        }

        return response()->json($response);
    }
}
