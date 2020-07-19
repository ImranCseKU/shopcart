<?php

namespace FleetCart\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class SslCommerzPaymentController extends Controller
{
    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $success_status = DB::table('orders')->where('id', $tran_id)->update(['status' => 'completed']);

        session()->flash('message', 'Order placed successfull.');
        session()->flash('type', 'success');
        return redirect('/');

        
    }


    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $order_detials = DB::table('orders')->where('id', $tran_id)->first();


        if ($order_detials->status == 'completed') {
            echo "Transaction is already Successful";
        } 
        else {

            $success_status = DB::table('orders')->where('id', $tran_id)->update(['status' => 'canceled']);
            session()->flash('message', 'Payment failed.Correct credentials needed for payment.');
            session()->flash('type', 'danger');
            return redirect('/');
        }
        
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $order_detials = DB::table('orders')->where('id', $tran_id)->first();


        if ($order_detials->status == 'completed') {
            echo "Transaction is already Successful";
        } 
        else {

            $success_status = DB::table('orders')->where('id', $tran_id)->update(['status' => 'canceled']);
            session()->flash('message', 'Payment canceled.');
            session()->flash('type', 'danger');
            return redirect('/');
        }

    }





}
