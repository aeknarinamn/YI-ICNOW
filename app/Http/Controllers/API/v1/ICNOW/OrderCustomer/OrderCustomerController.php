<?php

namespace YellowProject\Http\Controllers\API\v1\ICNOW\OrderCustomer;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ICNOW\OrderCustomer\OrderCustomer;
use YellowProject\ICNOW\OrderCustomer\CustomerShippingAddress;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItem;
use YellowProject\ICNOW\Product\ProductImages;
use YellowProject\ICNOW\OrderCustomer\OrderCustomerHistory;
use YellowProject\LineUserProfile;
use YellowProject\User;

class OrderCustomerController extends Controller
{
    public function orderStatusListing(Request $request)
    {
        $filters = $request->filter_items;
        $limit = $request->limit;
        $offset = $request->offset;
        $startDate = $filters['start_date'];
        $endDate = $filters['end_date'];
        $miniOperator = $filters['mini_operator'];
        $orderNo = $filters['order_no'];
        $phoneNumber = $filters['phone_number'];
        $status = $filters['status'];
        $customerName = $filters['customer_name'];
    	$datas = \DB::table('fact_icnow_customer_order as co')
    		->select(
    			'co.id',
    			'csa.first_name as customer_name',
    			'csa.phone_number as customer_phonenumber',
    			'co.order_no',
    			\DB::raw('(select sum(sci.retial_price) from fact_icnow_shopping_cart_item as sci where sci.shopping_cart_id = co.shopping_cart_id) as retail_price'),
    			'co.mini_name as mini_operator',
    			\DB::raw('DATE_FORMAT(co.created_at, "%d%m%Y %H:%i") as created_at'),
    			'co.status'
    		)
    		->leftjoin('fact_icnow_customer_shipping_address as csa','co.address_id','=','csa.id');
        if($startDate != "" && $endDate != ""){
            $datas = $datas->whereDate('co.created_at','>=',$startDate)->whereDate('co.created_at','<=',$endDate);
        }
        if($status != ""){
            $datas = $datas->where('co.status','like','%'.$status.'%');
        }
        if($miniOperator != ""){
            // $datas = $datas->where('co.status',$miniOperator);
        }
        if($orderNo != ""){
            $datas = $datas->where('co.order_no','like','%'.$orderNo.'%');
        }
        if($phoneNumber != ""){
            $datas = $datas->where('csa.phone_number','like','%'.$phoneNumber.'%');
        }
        if($customerName != ""){
            $datas = $datas->where('csa.first_name','like','%'.$customerName.'%');
        }
        $count = $datas->count();
    	$datas = $datas->orderByDesc('co.created_at')->skip($offset)->take($limit)->get();

		return response()->json([
            'rows' => $datas,
            'total' => $count,
        ]);
    }

    public function orderStatusDetail($id)
    {
    	$orderCustomer = OrderCustomer::find($id);
        $lineUserProfile = LineUserProfile::find($orderCustomer->line_user_id);
    	$shoppingCartItems = ShoppingCartItem::where('shopping_cart_id',$orderCustomer->shopping_cart_id)->get();
    	$customerShippingAddress = CustomerShippingAddress::find($orderCustomer->address_id);
    	$beforeDiscount = $shoppingCartItems->sum('before_price_discount');
    	$retialPrice = $shoppingCartItems->sum('retial_price');
    	$discountPrice = $beforeDiscount - $retialPrice;
    	$datas = [];
    	$datas['order_detail'] = [];
    	$datas['customer_information'] = [];
    	$datas['shipping_address'] = [];
    	$datas['shopping_carts'] = [];
    	$datas['id'] = $orderCustomer->id;
    	$datas['before_discount_price'] = $beforeDiscount;
    	$datas['discount_price'] = $discountPrice;
    	$datas['coupon_code'] = null;
    	$datas['coupon_discount_price'] = null;
    	$datas['sum_total'] = $retialPrice;
    	$datas['shipping_cost'] = null;
    	$datas['grand_total'] = $retialPrice;
    	$datas['order_detail']['order_no'] = $orderCustomer->order_no;
    	$datas['order_detail']['order_date'] = $orderCustomer->created_at->format('Y-m-d');
    	$datas['order_detail']['order_time'] = $orderCustomer->created_at->format('H:i');
    	$datas['order_detail']['order_status'] = $orderCustomer->status;
    	$datas['order_detail']['grand_total'] = $retialPrice;
    	$datas['order_detail']['deliver_date'] = \Carbon\Carbon::createFromFormat('d/m/y', $orderCustomer->date_of_delivery)->format('Y-m-d');
    	$datas['order_detail']['deliver_time'] = $orderCustomer->time_of_delivery;
    	$datas['customer_information']['customer_id'] = $lineUserProfile->customer_id;
    	$datas['customer_information']['first_name'] = $customerShippingAddress->first_name;
    	$datas['customer_information']['last_name'] = $customerShippingAddress->last_name;
    	$datas['customer_information']['email'] = $lineUserProfile->email;
    	$datas['customer_information']['phone_number'] = $customerShippingAddress->phone_number;
    	$datas['customer_information']['reward_point'] = null;
        $datas['shipping_address']['address_id'] = $customerShippingAddress->id;
    	$datas['shipping_address']['first_name'] = $customerShippingAddress->first_name;
    	$datas['shipping_address']['last_name'] = $customerShippingAddress->last_name;
    	$datas['shipping_address']['address'] = $customerShippingAddress->address;
    	$datas['shipping_address']['province'] = $customerShippingAddress->province;
    	$datas['shipping_address']['district'] = $customerShippingAddress->district;
    	$datas['shipping_address']['sub_district'] = $customerShippingAddress->sub_district;
    	$datas['shipping_address']['post_code'] = $customerShippingAddress->post_code;
    	$datas['shipping_address']['phone_number'] = $customerShippingAddress->phone_number;
    	$datas['shipping_address']['latitude'] = $customerShippingAddress->lattitude;
    	$datas['shipping_address']['longtitude'] = $customerShippingAddress->longtitude;
    	foreach ($shoppingCartItems as $key => $shoppingCartItem) {
    		$productImages = ProductImages::where('icnow_product_id',$shoppingCartItem->product_id)->first();
    		$datas['shopping_carts'][$key]['product_name'] = $shoppingCartItem->product_name;
    		$datas['shopping_carts'][$key]['section_id'] = $shoppingCartItem->section_id;
    		$datas['shopping_carts'][$key]['image_url'] = ($productImages)? $productImages->img_url : null;
    		$datas['shopping_carts'][$key]['sku'] = $shoppingCartItem->sku;
    		$datas['shopping_carts'][$key]['price'] = $shoppingCartItem->price;
    		$datas['shopping_carts'][$key]['special_price'] = $shoppingCartItem->special_price;
    		$datas['shopping_carts'][$key]['quantity'] = $shoppingCartItem->quantity;
    		$datas['shopping_carts'][$key]['total'] = $shoppingCartItem->retial_price;
    		$datas['shopping_carts'][$key]['details'] = [];
    		if($shoppingCartItem->section_id == 1){
    			$shoppingCartItemDetailDiy = $shoppingCartItem->shoppingCartItemDetailDiy;
    			$shoppingCartItemDetailDiyItems = $shoppingCartItemDetailDiy->shoppingCartItemDetailDiyItems;
    			$datas['shopping_carts'][$key]['details']['person_in_party'] = $shoppingCartItemDetailDiy->person_in_party;
    			$datas['shopping_carts'][$key]['details']['product_focus'] = $shoppingCartItemDetailDiyItems->pluck('value')->toArray();
    			$datas['shopping_carts'][$key]['details']['comment'] = $shoppingCartItemDetailDiy->comment;
    		}else if($shoppingCartItem->section_id == 3){
                $shoppingCartItemDetailCustoms = $shoppingCartItem->shoppingCartItemDetailCustoms;
                $datas['shopping_carts'][$key]['details']['group_items'] = [];
                foreach ($shoppingCartItemDetailCustoms as $keyPartySet => $shoppingCartItemDetailCustom) {
                    $shoppingCartItemDetailCustomItems = $shoppingCartItemDetailCustom->shoppingCartItemDetailCustomItems;
                    $datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['group_name'] = $shoppingCartItemDetailCustom->group_name;
                    $datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['choose_item'] = $shoppingCartItemDetailCustom->choose_item;
                    $datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['max_item'] = $shoppingCartItemDetailCustom->max_item;
                    $datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['items'] = [];
                    foreach ($shoppingCartItemDetailCustomItems as $keyPartySetItem => $shoppingCartItemDetailCustomItem) {
                        $datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['items'][$keyPartySetItem]['item_name'] = $shoppingCartItemDetailCustomItem->item_name;
                        $datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['items'][$keyPartySetItem]['item_value'] = $shoppingCartItemDetailCustomItem->item_value;
                        $datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['items'][$keyPartySetItem]['price'] = $shoppingCartItemDetailCustomItem->price;
                    }
                }
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

		return response()->json([
            'datas' => $datas,
        ]);
    }

    public function orderStatusHistory(Request $request)
    {
        $filterItems = $request->filter_items;
        $datas = \DB::table('fact_icnow_customer_order_history as coh')
            ->select(
                'coh.username',
                \DB::raw("CONCAT(coh.from_status,'=>',coh.to_status) as description"),
                \DB::raw('DATE_FORMAT(coh.created_at, "%Y-%m-%d") as created_at')
            )
            ->where('order_id',$filterItems['order_id'])
            ->get();
  //   	$datas = [
		// 	[
		// 		"username" => "usera",
		// 		"desc" => "desc",
		// 		"created_at" => "2018-09-01"
		// 	],
		// 	[
		// 		"username" => "userb",
		// 		"desc" => "desc",
		// 		"created_at" => "2018-09-01"
		// 	]
		// ];

    	return response()->json([
            'datas' => $datas,
        ]);
    }

    public function customerProfileListing(Request $request)
    {
        $filters = $request->filter_items;
        $limit = $request->limit;
        $offset = $request->offset;
        $startDate = $filters['start_date'];
        $endDate = $filters['end_date'];
        $firstName = $filters['first_name'];
        $lastName = $filters['last_name'];
        $phoneNumber = $filters['phone_number'];
        $count = 0;
        $datas = \DB::table('dim_line_user_table as lu')
            ->select(
                'lu.id',
                'csa.first_name',
                'csa.last_name',
                'csa.phone_number',
                \DB::raw('(select count(*) from fact_icnow_shopping_cart as sc where sc.line_user_id = lu.id) as all_purchase'),
                \DB::raw('(select count(*) from fact_icnow_shopping_cart as sc where sc.line_user_id = lu.id) as complete_purchase'),
                \DB::raw('(select sum(sci.retial_price) from fact_icnow_shopping_cart_item as sci where sci.line_user_id = lu.id) as total_amount'),
                \DB::raw('DATE_FORMAT(lu.created_at, "%Y-%m-%d") as register_date')
            )
            ->leftjoin('fact_icnow_customer_shipping_address as csa','lu.address_id','=','csa.id');
        if($startDate != "" && $endDate != ""){
            $datas = $datas->whereDate('lu.created_at','>=',$startDate)->whereDate('lu.created_at','<=',$endDate);
        }
        if($firstName != ""){
            $datas = $datas->where('csa.first_name','like','%'.$firstName.'%');
        }
        if($lastName != ""){
            $datas = $datas->where('csa.last_name','like','%'.$lastName.'%');
        }
        if($phoneNumber != ""){
            $datas = $datas->where('csa.phone_number','like','%'.$phoneNumber.'%');
        }
        $count = $datas->count();
        $datas = $datas->skip($offset)->take($limit)->get();
  //   	$datas = [
		// 	[
		// 		"id" => 1,
		// 		"first_name" => "TEST",
		// 		"last_name" => "TEST",
		// 		"phone_number" => "06255648845",
		// 		"all_purchase" => 100,
		// 		"complete_purchase" => 10,
		// 		"total_amount" => 9999999.00,
		// 		"register_date" => "2018-09-01"
		// 	],
		// 	[
		// 		"id" => 2,
		// 		"first_name" => "TEST2",
		// 		"last_name" => "TEST2",
		// 		"phone_number" => "0628454685",
		// 		"all_purchase" => 80,
		// 		"complete_purchase" => 0,
		// 		"total_amount" => 9999999.00,
		// 		"register_date" => "2018-09-01"
		// 	]
		// ];

    	return response()->json([
            'rows' => $datas,
            'total' => $count,
        ]);
    }

    public function customerProfileDetail(Request $request,$id)
    {
        $datas = [];
        $lineUserProfile = LineUserProfile::find($id);
        $customerShippingAddress = CustomerShippingAddress::find($lineUserProfile->address_id);
        $orders = \DB::table('fact_icnow_customer_order as co')
            ->select(
                'co.id',
                'csa.first_name as customer_name',
                'csa.phone_number as customer_phonenumber',
                'co.order_no',
                \DB::raw('(select sum(sci.retial_price) from fact_icnow_shopping_cart_item as sci where sci.shopping_cart_id = co.shopping_cart_id) as retail_price'),
                'co.mini_name as mini_operator',
                \DB::raw('DATE_FORMAT(co.created_at, "%d%m%Y %H:%i") as created_at'),
                'co.status'
            )
            ->leftjoin('fact_icnow_customer_shipping_address as csa','co.address_id','=','csa.id')
            ->where('co.line_user_id',$lineUserProfile->id)
            ->get();
        $datas['id'] = $id;
        $datas['customer_information']['customer_id'] = $lineUserProfile->customer_id;
        $datas['customer_information']['first_name'] = null;
        $datas['customer_information']['last_name'] = null;
        if($customerShippingAddress){
            $datas['customer_information']['first_name'] = $customerShippingAddress->first_name;
            $datas['customer_information']['last_name'] = $customerShippingAddress->last_name;
        }
        $datas['customer_information']['email'] = $lineUserProfile->email;
        $datas['customer_information']['phone_number'] = $lineUserProfile->phone_number;
        $datas['customer_information']['reward_point'] = null;
        $datas['shipping_address']['address_id'] = null;
        $datas['shipping_address']['first_name'] = null;
        $datas['shipping_address']['last_name'] = null;
        $datas['shipping_address']['address'] = null;
        $datas['shipping_address']['province'] = null;
        $datas['shipping_address']['district'] = null;
        $datas['shipping_address']['sub_district'] = null;
        $datas['shipping_address']['post_code'] = null;
        $datas['shipping_address']['phone_number'] = null;
        $datas['shipping_address']['latitude'] = null;
        $datas['shipping_address']['longtitude'] = null;
        if($customerShippingAddress){
            $datas['shipping_address']['address_id'] = $customerShippingAddress->id;
            $datas['shipping_address']['first_name'] = $customerShippingAddress->first_name;
            $datas['shipping_address']['last_name'] = $customerShippingAddress->last_name;
            $datas['shipping_address']['address'] = $customerShippingAddress->address;
            $datas['shipping_address']['province'] = $customerShippingAddress->province;
            $datas['shipping_address']['district'] = $customerShippingAddress->district;
            $datas['shipping_address']['sub_district'] = $customerShippingAddress->sub_district;
            $datas['shipping_address']['post_code'] = $customerShippingAddress->post_code;
            $datas['shipping_address']['phone_number'] = $customerShippingAddress->phone_number;
            $datas['shipping_address']['latitude'] = $customerShippingAddress->lattitude;
            $datas['shipping_address']['longtitude'] = $customerShippingAddress->longtitude;
        }
        $datas['recent_orders'] = [];
        $datas['recent_orders'] = $orders->toArray();
  //   	$datas = [
		// 	"id" => 1,
		// 	"customer_information" => [
		// 		"customer_id" => "C000001",
		// 		"first_name" => "TEST",
		// 		"last_name" => "TEST",
		// 		"email" => "test@test.com",
		// 		"phone_number" => "0625555555",
		// 		"reward_point" => 10
		// 	],
		// 	"shipping_address" => [
		// 		"first_name" => "TEST",
		// 		"last_name" => "TEST",
		// 		"address" => "address1",
		// 		"province" => "กรุงเทพมหานคร",
		// 		"district" => "เขตลาดกระบัง",
		// 		"sub_district" => "คลองสองต้นนุ่น",
		// 		"post_code" => "10110",
		// 		"phone_number" => "062555555",
		// 		"latitude" => "10.545464",
		// 		"longtitude" => "103.5454545"
		// 	],
		// 	"recent_orders" => [
		// 		[
		// 			"id" => 1,
		// 			"order_no" => "wall's00001",
		// 			"product_name" => "product A",
		// 			"image_url" => "https=>//test.com/image",
		// 			"sku" => "0000001",
		// 			"price" => 200.00,
		// 			"special_price" => 200.00,
		// 			"quantity" => 1,
		// 			"total" => 200,
		// 			"status" => "คำสั่งซื้อใหม่"
		// 		],
		// 		[
		// 			"id" => 2,
		// 			"order_no" => "wall's00002",
		// 			"product_name" => "product B",
		// 			"image_url" => "https=>//test.com/image",
		// 			"sku" => "0000002",
		// 			"price" => 200.00,
		// 			"special_price" => 200.00,
		// 			"quantity" => 1,
		// 			"total" => 200,
		// 			"status" => "คำสั่งซื้อใหม่"
		// 		]
		// 	]
		// ];

    	return response()->json([
            'datas' => $datas,
        ]);
    }

    public function updateDataOrder(Request $request)
    {
        $user = User::find($request->user_id);
        $orderCustomer = OrderCustomer::find($request->order_id);
        OrderCustomerHistory::create([
            'order_id' => $request->order_id,
            'order_no' => $orderCustomer->order_no,
            'user_id' => $user->id,
            'email' => $user->email,
            'username' => $user->username,
            'from_status' => $orderCustomer->status,
            'to_status' => $request->status,
        ]);

        $orderCustomer->update([
            'status' => $request->status
        ]);

    	return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function updateDataProfile(Request $request)
    {

    	return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
        ]);
    }
}
