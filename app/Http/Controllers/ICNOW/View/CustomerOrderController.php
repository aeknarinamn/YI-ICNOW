<?php

namespace YellowProject\Http\Controllers\ICNOW\View;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ICNOW\OrderCustomer\OrderCustomer;
use YellowProject\ICNOW\ShoppingCart\ShoppingCart;
use YellowProject\ICNOW\CoreLineFunction\CoreLineFunction;
use YellowProject\ICNOW\APIConnection\MiniConnection;
use YellowProject\ICNOW\OrderCustomer\CustomerShippingAddress;
use YellowProject\ICNOW\Mini\Mini;
use YellowProject\LineUserProfile;
use YellowProject\ICNOW\Log\LogSession;
use Carbon\Carbon;

class CustomerOrderController extends Controller
{
    public function submitOrder(Request $request)
    {
        $status = "คำสั่งซื้อใหม่";
        if(!array_key_exists('line-user-id', $_COOKIE)){
            abort(404);
        }
        $lineUserId = $_COOKIE['line-user-id'];
        // $lat = "";
        // if(array_key_exists('address-lat', $_COOKIE)){
        //     $lat = $_COOKIE['address-lat'];
        // }
        // $long = "";
        // if(array_key_exists('address-long', $_COOKIE)){
        //     $long = $_COOKIE['address-long'];
        // }
        $lineUserProfile = LineUserProfile::find($lineUserId);
        $logSession = LogSession::where('line_user_id',$lineUserProfile->id)->where('is_active',1)->first();
        if($logSession){
            $logSession->update([
                'is_check_out' => 1
            ]);
        }
        $customerShippingAddress = CustomerShippingAddress::find($lineUserProfile->address_id);
        $customerShippingAddress->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'remark' => $request->remark,
        ]);
        $response = MiniConnection::connectMini($customerShippingAddress->lattitude,$customerShippingAddress->longtitude);
        // $response = MiniConnection::connectMini($lat,$long);
        if($response != null){
            $mini = Mini::where('mini_code',$response->code)->first();
            if($mini){
                $request['mini_id'] = $mini->id;
                $request['mini_code'] = $response->code;
                $request['mini_name'] = $response->name;
                $request['dt_code'] = $response->dt_code;
            }else{
                $status = "อยู่นอกพื้นที่ให้บริการ";
                // return view('icnow.out-service.index');
            }
        }else{
            $status = "อยู่นอกพื้นที่ให้บริการ";
            // return view('icnow.out-service.index');
        }
        // if($lineUserProfile){
        //     $lineUserProfile->update([
        //         'address_id' => $request->address_id
        //     ]);
        // }

    	$shoppingCart = ShoppingCart::where('line_user_id',$lineUserId)->where('is_active',1)->first();
        if(!$shoppingCart){
            abort(404);
        }
    	if($shoppingCart){
    		$shoppingCart->update([
    			'is_active' => 0
    		]);
    		$request['shopping_cart_id'] = $shoppingCart->id;
            $request['status'] = $status;
    		$request['order_no'] = OrderCustomer::genOrderNumber();
            $expDate = "";
            $nowTime = Carbon::now()->format('H:i:s');
            $expDate = Carbon::now()->addMinutes(10)->format('Y-m-d H:i:s');
            // if(($nowTime >= "18:00:00" && $nowTime <= "23:59:59") || ($nowTime >= "00:00:00" && $nowTime <= "08:00:00" )){
            //     $expDate = Carbon::now()->addDays(1)->format('Y-m-d');
            //     $expDate = $expDate." 10:00:00";
            // }else{
            //     $expDate = Carbon::now()->addHours(2)->format('Y-m-d H:i:s');
            // }
            $request['exp_time'] = $expDate;
            $request['address_id'] = $customerShippingAddress->id;
    		$orderCustomer = OrderCustomer::create($request->all());
            $orderCustomer->update([
                'customer_submit_shopping_cart_date' => $shoppingCart->updated_at->format('Y-m-d'),
                'customer_submit_shopping_cart_time' => $shoppingCart->updated_at->format('H:i'),
                'customer_submit_order_date' => Carbon::now()->format('Y-m-d'),
                'customer_submit_order_time' => Carbon::now()->format('H:i')
            ]);
            if($logSession){
                $logSession->update([
                    'order_id' => $orderCustomer->id
                ]);
            }
    	}
        // $lineUserProfile = LineUserProfile::find($lineUserId);
        // if($lineUserProfile){
        //     $lineUserProfile->update([
        //         'address_id' => $request->address_id
        //     ]);
        // }

        if($status == 'อยู่นอกพื้นที่ให้บริการ'){
            return view('icnow.out-service.index');
        }else{
            CoreLineFunction::pushMessageToCustomerOrder($lineUserProfile,$orderCustomer);
            return redirect('/thank');
        }
    }

    public function ratingPage(Request $request)
    {
        $orderId = $request->order_id;
        $orderCustomer = OrderCustomer::find($orderId);

        if($orderCustomer->is_rating == 1){
            return view('icnow.rating.rating-thank');
        }

        return view('icnow.rating.index')
            ->with('order_id',$orderId);
    }

    public function submitRating(Request $request)
    {
        $orderId = $request->order_id;
        $orderCustomer = OrderCustomer::find($orderId);
        if($orderCustomer){
            $orderCustomer->update([
                'is_rating' => 1,
                'rating_1' => $request->rating_1,
                'rating_2' => $request->rating_2,
                'rating_3' => $request->rating_3,
                'rating_4' => $request->rating_4,
                'suggestion' => $request->suggestion,
            ]);
        }

        return view('icnow.rating.rating-thank');
    }

    public function outServicePage()
    {
        return view('icnow.out-service.index');
    }
}
