<?php

namespace YellowProject\Http\Controllers\ICNOW\Mini;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ICNOW\OrderCustomer\OrderCustomer;
use YellowProject\ICNOW\OrderCustomer\CustomerShippingAddress;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItem;
use YellowProject\ICNOW\Product\ProductImages;
use YellowProject\ICNOW\OrderCustomer\OrderCustomerHistory;
use YellowProject\ICNOW\Mini\MiniUser;
use YellowProject\ICNOW\CoreLineFunction\CoreLineFunction;

class MiniController extends Controller
{
    public function mainPage(Request $request)
    {
    	if(!array_key_exists('Ji4TM6ckZDkBFDZVz0qM', $_COOKIE)){
            return redirect('/mini-login');
        }
        if(array_key_exists('by-pass', $_COOKIE)){
            setcookie('by-pass', "", time() - (300 * 1), "/");
            $orderCustomer = OrderCustomer::where('order_no',$_COOKIE['by-pass'])->first();
            if($orderCustomer){
                if($orderCustomer->status == 'คำสั่งซื้อใหม่'){
                    return redirect('/mini-order-detail/'.$orderCustomer->id);
                }else{
                    return redirect('/mini-order-detail-cf/'.$orderCustomer->id."?status=cf");
                }
            }
        }
        $user = json_decode($_COOKIE['Ji4TM6ckZDkBFDZVz0qM']);
    	$orderCustomers = \DB::table('fact_icnow_customer_order as co')
    		->select(
    			'co.id',
    			'csa.first_name',
    			'csa.last_name',
    			'csa.phone_number',
    			'co.order_no',
    			'co.status'
    		)
    		->leftjoin('fact_icnow_customer_shipping_address as csa','co.address_id','=','csa.id')
    		->where('dt_code',$user->dt_code)
    		->get();
    	$newOrderCustomers = $orderCustomers->filter(function ($value, $key) {
		    return $value->status == 'คำสั่งซื้อใหม่';
		});
		$waitingDeliveries = $orderCustomers->filter(function ($value, $key) {
		    return $value->status == 'รอการจัดส่ง';
		});
		$completeDeliveries = $orderCustomers->filter(function ($value, $key) {
		    return $value->status == 'จัดส่งเรียบร้อย';
		});
		$cancleOrders = $orderCustomers->filter(function ($value, $key) {
		    return $value->status == 'ยกเลิกรายการสั่งซื้อ' || $value->status == 'ยกเลิกรายการสั่งซื้อโดยมินิ';
		});
		$cancleOrderSystems = $orderCustomers->filter(function ($value, $key) {
		    return $value->status == 'ยกเลิกโดยระบบ';
		});

    	return view('icnow.mini.main.index')
    		->with('newOrderCustomers',$newOrderCustomers)
    		->with('waitingDeliveries',$waitingDeliveries)
    		->with('completeDeliveries',$completeDeliveries)
    		->with('cancleOrderSystems',$cancleOrderSystems)
    		->with('cancleOrders',$cancleOrders);
    }

    public function mainPageByPass($orderNo)
    {
        setcookie('by-pass', $orderNo, time() + (300 * 1), "/");

        return redirect('/bc/MiniLogin');
    }

    public function orderDetail($id)
    {
    	if(!array_key_exists('Ji4TM6ckZDkBFDZVz0qM', $_COOKIE)){
            return redirect('/mini-login');
        }
    	$orderCustomer = \DB::table('fact_icnow_customer_order as co')
    		->select(
    			'co.id',
    			'co.shopping_cart_id',
    			'csa.first_name',
    			'csa.last_name',
    			'csa.phone_number',
    			'csa.address',
    			'csa.sub_district',
    			'csa.district',
                'csa.province',
                'csa.lattitude',
    			'csa.longtitude',
    			'co.order_no',
    			'co.date_of_delivery',
    			'co.time_of_delivery',
    			'co.status'
    		)
    		->leftjoin('fact_icnow_customer_shipping_address as csa','co.address_id','=','csa.id')
    		->where('co.id',$id)
    		->first();
    	$shoppingCartItems = ShoppingCartItem::where('shopping_cart_id',$orderCustomer->shopping_cart_id)->get();
    	$retialPrice = $shoppingCartItems->sum('retial_price');
    	$datas = [];
    	foreach ($shoppingCartItems as $key => $shoppingCartItem) {
    		$productImages = ProductImages::where('icnow_product_id',$shoppingCartItem->product_id)->first();
    		$datas['shopping_carts'][$key]['product_name'] = $shoppingCartItem->product_name;
    		$datas['shopping_carts'][$key]['section_id'] = $shoppingCartItem->section_id;
    		$datas['shopping_carts'][$key]['image_url'] = ($productImages)? $productImages->img_url : null;
    		$datas['shopping_carts'][$key]['quantity'] = $shoppingCartItem->quantity;
    		$datas['shopping_carts'][$key]['details'] = [];
    		if($shoppingCartItem->section_id == 1){
    			$shoppingCartItemDetailDiy = $shoppingCartItem->shoppingCartItemDetailDiy;
                $shoppingCartItemDetailDiyItems = $shoppingCartItemDetailDiy->shoppingCartItemDetailDiyItems;
                $datas['shopping_carts'][$key]['details']['person_in_party'] = $shoppingCartItemDetailDiy->person_in_party;
    			$datas['shopping_carts'][$key]['details']['other_option'] = $shoppingCartItemDetailDiy->other_option;
    			$datas['shopping_carts'][$key]['details']['product_focus'] = $shoppingCartItemDetailDiyItems->pluck('value')->toArray();
    			$datas['shopping_carts'][$key]['details']['comment'] = $shoppingCartItemDetailDiy->comment;
    		}else{
    			$shoppingCartItemDetailPartySets = $shoppingCartItem->shoppingCartItemDetailPartySets;
    			$datas['shopping_carts'][$key]['details']['group_items'] = [];
    			foreach ($shoppingCartItemDetailPartySets as $keyPartySet => $shoppingCartItemDetailPartySet) {
    				$shoppingCartItemDetailPartySetItems = $shoppingCartItemDetailPartySet->shoppingCartItemDetailPartySetItems;
    				$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['group_name'] = $shoppingCartItemDetailPartySet->group_name;
    				$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['choose_item'] = $shoppingCartItemDetailPartySet->choose_item;
    				$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['max_item'] = $shoppingCartItemDetailPartySet->max_item;
    				$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['items'] = [];
    				foreach ($shoppingCartItemDetailPartySetItems as $keyPartySetItem => $shoppingCartItemDetailPartySetItem) {
    					$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['items'][$keyPartySetItem]['item_name'] = $shoppingCartItemDetailPartySetItem->item_name;
    					$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['items'][$keyPartySetItem]['item_value'] = $shoppingCartItemDetailPartySetItem->item_value;
    				}

    			}
    		}
    	}

    	return view('icnow.mini.order-detail.index')
    		->with('orderCustomer',$orderCustomer)
    		->with('retialPrice',$retialPrice)
    		->with('datas',$datas['shopping_carts']);
    }

    public function orderDetailSuccess(Request $request,$id)
    {
    	if(!array_key_exists('Ji4TM6ckZDkBFDZVz0qM', $_COOKIE)){
            return redirect('/mini-login');
        }
        $text = "";
        $mainStatus = $request->status;
        if($mainStatus == 'cd'){
            $text = "จัดส่งเรียบร้อย";
        }else if($mainStatus == 'co'){
            $text = "ยกเลิกรายการสั่งซื้อ";
        }else if($mainStatus == 'cos'){
            $text = "ยกเลิกรายการโดยระบบ";
        }else if($mainStatus == 'wd'){
            $text = "รอการจัดส่ง";
        }else if($mainStatus == 'cf'){
            $text = "ยืนยันคำสั่งซื้อเรียบร้อยแล้ว";
        }
    	$orderCustomer = \DB::table('fact_icnow_customer_order as co')
    		->select(
    			'co.id',
    			'co.shopping_cart_id',
    			'csa.first_name',
    			'csa.last_name',
    			'csa.phone_number',
    			'csa.address',
    			'csa.sub_district',
    			'csa.district',
    			'csa.province',
                'csa.lattitude',
                'csa.longtitude',
    			'co.order_no',
    			'co.date_of_delivery',
    			'co.time_of_delivery',
    			'co.status'
    		)
    		->leftjoin('fact_icnow_customer_shipping_address as csa','co.address_id','=','csa.id')
    		->where('co.id',$id)
    		->first();
    	$shoppingCartItems = ShoppingCartItem::where('shopping_cart_id',$orderCustomer->shopping_cart_id)->get();
    	$retialPrice = $shoppingCartItems->sum('retial_price');
    	$datas = [];
    	foreach ($shoppingCartItems as $key => $shoppingCartItem) {
    		$productImages = ProductImages::where('icnow_product_id',$shoppingCartItem->product_id)->first();
    		$datas['shopping_carts'][$key]['product_name'] = $shoppingCartItem->product_name;
    		$datas['shopping_carts'][$key]['section_id'] = $shoppingCartItem->section_id;
    		$datas['shopping_carts'][$key]['image_url'] = ($productImages)? $productImages->img_url : null;
    		$datas['shopping_carts'][$key]['quantity'] = $shoppingCartItem->quantity;
    		$datas['shopping_carts'][$key]['details'] = [];
    		if($shoppingCartItem->section_id == 1){
    			$shoppingCartItemDetailDiy = $shoppingCartItem->shoppingCartItemDetailDiy;
    			$shoppingCartItemDetailDiyItems = $shoppingCartItemDetailDiy->shoppingCartItemDetailDiyItems;
    			$datas['shopping_carts'][$key]['details']['person_in_party'] = $shoppingCartItemDetailDiy->person_in_party;
                $datas['shopping_carts'][$key]['details']['other_option'] = $shoppingCartItemDetailDiy->other_option;
    			$datas['shopping_carts'][$key]['details']['product_focus'] = $shoppingCartItemDetailDiyItems->pluck('value')->toArray();
    			$datas['shopping_carts'][$key]['details']['comment'] = $shoppingCartItemDetailDiy->comment;
    		}else{
    			$shoppingCartItemDetailPartySets = $shoppingCartItem->shoppingCartItemDetailPartySets;
    			$datas['shopping_carts'][$key]['details']['group_items'] = [];
    			foreach ($shoppingCartItemDetailPartySets as $keyPartySet => $shoppingCartItemDetailPartySet) {
    				$shoppingCartItemDetailPartySetItems = $shoppingCartItemDetailPartySet->shoppingCartItemDetailPartySetItems;
    				$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['group_name'] = $shoppingCartItemDetailPartySet->group_name;
    				$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['choose_item'] = $shoppingCartItemDetailPartySet->choose_item;
    				$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['max_item'] = $shoppingCartItemDetailPartySet->max_item;
    				$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['items'] = [];
    				foreach ($shoppingCartItemDetailPartySetItems as $keyPartySetItem => $shoppingCartItemDetailPartySetItem) {
    					$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['items'][$keyPartySetItem]['item_name'] = $shoppingCartItemDetailPartySetItem->item_name;
    					$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['items'][$keyPartySetItem]['item_value'] = $shoppingCartItemDetailPartySetItem->item_value;
    				}

    			}
    		}
    	}

    	return view('icnow.mini.order-detail-cf.index')
    		->with('orderCustomer',$orderCustomer)
    		->with('retialPrice',$retialPrice)
    		->with('datas',$datas['shopping_carts'])
            ->with('text',$text);
    }

    public function acceptOrder($id)
    {
    	if(!array_key_exists('Ji4TM6ckZDkBFDZVz0qM', $_COOKIE)){
            return redirect('/mini-login');
        }
    	$orderCustomer = \DB::table('fact_icnow_customer_order as co')
    		->select(
                'co.id',
                'co.line_user_id',
    			'co.dt_code',
    			'co.shopping_cart_id',
    			'csa.first_name',
    			'csa.last_name',
    			'csa.phone_number',
    			'csa.address',
    			'csa.sub_district',
    			'csa.district',
    			'csa.province',
                'csa.lattitude',
                'csa.longtitude',
    			'co.order_no',
    			'co.date_of_delivery',
    			'co.time_of_delivery',
    			'co.status'
    		)
    		->leftjoin('fact_icnow_customer_shipping_address as csa','co.address_id','=','csa.id')
    		->where('co.id',$id)
    		->first();
        if($orderCustomer->status != "คำสั่งซื้อใหม่"){
            return redirect('/mini-page');
        }
    	$shoppingCartItems = ShoppingCartItem::where('shopping_cart_id',$orderCustomer->shopping_cart_id)->get();
    	$retialPrice = $shoppingCartItems->sum('retial_price');
    	if($orderCustomer){
    		$orderCustomerMain = OrderCustomer::find($id);
    		$orderCustomerMain->update([
                'status' => 'รอการจัดส่ง',
    			'accept_delivery_date' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
    		]);
            CoreLineFunction::pushMessageToCustomerConfirmOrder($orderCustomer);
    	}

    	$datas = [];
    	foreach ($shoppingCartItems as $key => $shoppingCartItem) {
    		$productImages = ProductImages::where('icnow_product_id',$shoppingCartItem->product_id)->first();
    		$datas['shopping_carts'][$key]['product_name'] = $shoppingCartItem->product_name;
    		$datas['shopping_carts'][$key]['section_id'] = $shoppingCartItem->section_id;
    		$datas['shopping_carts'][$key]['image_url'] = ($productImages)? $productImages->img_url : null;
    		$datas['shopping_carts'][$key]['quantity'] = $shoppingCartItem->quantity;
    		$datas['shopping_carts'][$key]['details'] = [];
    		if($shoppingCartItem->section_id == 1){
    			$shoppingCartItemDetailDiy = $shoppingCartItem->shoppingCartItemDetailDiy;
    			$shoppingCartItemDetailDiyItems = $shoppingCartItemDetailDiy->shoppingCartItemDetailDiyItems;
    			$datas['shopping_carts'][$key]['details']['person_in_party'] = $shoppingCartItemDetailDiy->person_in_party;
                $datas['shopping_carts'][$key]['details']['other_option'] = $shoppingCartItemDetailDiy->other_option;
    			$datas['shopping_carts'][$key]['details']['product_focus'] = $shoppingCartItemDetailDiyItems->pluck('value')->toArray();
    			$datas['shopping_carts'][$key]['details']['comment'] = $shoppingCartItemDetailDiy->comment;
    		}else{
    			$shoppingCartItemDetailPartySets = $shoppingCartItem->shoppingCartItemDetailPartySets;
    			$datas['shopping_carts'][$key]['details']['group_items'] = [];
    			foreach ($shoppingCartItemDetailPartySets as $keyPartySet => $shoppingCartItemDetailPartySet) {
    				$shoppingCartItemDetailPartySetItems = $shoppingCartItemDetailPartySet->shoppingCartItemDetailPartySetItems;
    				$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['group_name'] = $shoppingCartItemDetailPartySet->group_name;
    				$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['choose_item'] = $shoppingCartItemDetailPartySet->choose_item;
    				$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['max_item'] = $shoppingCartItemDetailPartySet->max_item;
    				$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['items'] = [];
    				foreach ($shoppingCartItemDetailPartySetItems as $keyPartySetItem => $shoppingCartItemDetailPartySetItem) {
    					$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['items'][$keyPartySetItem]['item_name'] = $shoppingCartItemDetailPartySetItem->item_name;
    					$datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['items'][$keyPartySetItem]['item_value'] = $shoppingCartItemDetailPartySetItem->item_value;
    				}

    			}
    		}
    	}

    	return view('icnow.mini.confirm-order.index')
    		->with('orderCustomer',$orderCustomer)
    		->with('retialPrice',$retialPrice)
    		->with('datas',$datas['shopping_carts']);
    }

    public function cancleOrder($id)
    {
    	if(!array_key_exists('Ji4TM6ckZDkBFDZVz0qM', $_COOKIE)){
            return redirect('/mini-login');
        }
        
    	$orderCustomerMain = OrderCustomer::find($id);
        if($orderCustomerMain->status != "คำสั่งซื้อใหม่"){
            return redirect('/mini-page');
        }

    	return view('icnow.mini.cancle-order.reason')
    		->with('orderCustomerMain',$orderCustomerMain);
    }

    public function cancleOrderStore(Request $request)
    {
    	$id = $request->order_id;
    	$orderCustomerMain = OrderCustomer::find($id);
        if($orderCustomerMain->status != "คำสั่งซื้อใหม่"){
            return redirect('/mini-page');
        }
    	if($orderCustomerMain){
    		$orderCustomerMain->update([
    			'status' => 'ยกเลิกรายการสั่งซื้อ',
    			'cancle_case' => $request->cancle_case,
    			'cancle_comment' => $request->cancle_comment
    		]);
    	}
        CoreLineFunction::pushMessageToCustomerCancleOrder($orderCustomerMain);

    	return view('icnow.mini.cancle-order.finish');
    }

    public function miniCancleOrder($id)
    {
        if(!array_key_exists('Ji4TM6ckZDkBFDZVz0qM', $_COOKIE)){
            return redirect('/mini-login');
        }
        
        $orderCustomerMain = OrderCustomer::find($id);
        if($orderCustomerMain->status != "รอการจัดส่ง"){
            return redirect('/mini-page');
        }

        return view('icnow.mini.mini-cancle-order.reason')
            ->with('orderCustomerMain',$orderCustomerMain);
    }

    public function miniCancleOrderStore(Request $request)
    {
        $id = $request->order_id;
        $orderCustomerMain = OrderCustomer::find($id);
        if($orderCustomerMain->status != "รอการจัดส่ง"){
            return redirect('/mini-page');
        }
        if($orderCustomerMain){
            $orderCustomerMain->update([
                'status' => 'ยกเลิกรายการสั่งซื้อโดยมินิ',
                'cancle_case' => $request->cancle_case,
                'cancle_comment' => $request->cancle_comment
            ]);
        }
        CoreLineFunction::pushMessageToCustomerCancleOrder($orderCustomerMain);

        return view('icnow.mini.mini-cancle-order.finish');
    }

    public function checkLogin(Request $request)
    {
    	$username = $request->username;
        $password = $request->password;
        $user = MiniUser::where('username',$username)->first();
        if(!$user){
            return redirect()->back();
        }
        $userPass = $user->password;
        $dePass = base64_decode($userPass);
        $passwordReal = base64_decode($dePass);
        $passwordReal = str_replace('"', '', $passwordReal);
        $lineUserId = $_COOKIE['line-user-id'];
        $user->update([
        	'line_user_id' => $lineUserId
        ]);

        if ($password == $passwordReal)
        {
            setcookie('Ji4TM6ckZDkBFDZVz0qM', $user, time() + (60*60), "/");
            return redirect('/mini-page');
        }else{
            return redirect()->back();
        }
    }

    public function loginPage()
    {
    	if(!array_key_exists('line-user-id', $_COOKIE)){
    		abort(404);
    	}
    	if(array_key_exists('Ji4TM6ckZDkBFDZVz0qM', $_COOKIE)){
            return redirect('/mini-page');
        }

    	return view('icnow.mini.login.index');
    }

    public function logOutPage()
    {
        setcookie('Ji4TM6ckZDkBFDZVz0qM', "", time() - (60*60), "/");
        return redirect('/mini-login');
    }

    public function updateStatusDelivery($id)
    {
    	$orderCustomerMain = OrderCustomer::find($id);
        if($orderCustomerMain->status != "รอการจัดส่ง"){
            return redirect('/mini-page');
        }
    	if($orderCustomerMain){
    		$orderCustomerMain->update([
    			'status' => 'จัดส่งเรียบร้อย',
                'finish_delivery_date' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'mini_confirm_delivery_date' => \Carbon\Carbon::now()->format('Y-m-d'),
                'mini_confirm_delivery_time' => \Carbon\Carbon::now()->format('H:i')
    		]);
    	}
        CoreLineFunction::pushMessageToCustomerDeliver($orderCustomerMain);

    	return redirect('/mini-page');
    } 
}
