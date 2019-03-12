<?php

namespace YellowProject\Http\Controllers;

use Illuminate\Http\Request;
use YellowProject\Ecommerce\Order\OrderDTPayment;
use YellowProject\Ecommerce\Order\OrderPayment;

class RecieveEcommercePaymentController extends Controller
{
    public function recieveCode($code)
    {
        $authUser = \Session::get('line-login', '');
        $orderDTPayment = OrderDTPayment::where('payment_code',$code)->first();
        if(!$orderDTPayment){
            return view('errors.404');
        }
        $order = $orderDTPayment->order;
        $orderPayment = OrderPayment::where('order_id',$order->id)->first();
        if($orderPayment){
            return view('ecommerce.thank')
                ->with('text','ท่านได้ทำการแจ้งการชำระเงินเรียบร้อยแล้ว');
        }
        $customer = $orderDTPayment->customer;
        
        return view('ecommerce.choose-payment')
        	->with('order',$order)
        	->with('customer',$customer);
    }

    public function returnThank()
    {
        return view('ecommerce.thank')
                ->with('text','ท่านได้ทำการแจ้งการชำระเงินเรียบร้อยแล้ว');
    }
}
