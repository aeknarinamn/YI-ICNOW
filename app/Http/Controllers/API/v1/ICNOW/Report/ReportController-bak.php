<?php

namespace YellowProject\Http\Controllers\API\v1\ICNOW\Report;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ICNOW\OrderCustomer\OrderCustomer;
use YellowProject\ICNOW\Log\LogProductView;
use YellowProject\ICNOW\Log\LogSession;
use YellowProject\ICNOW\ShoppingCart\ShoppingCart;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItem;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailDiy;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailDiyItem;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailPartySet;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailPartySetItem;
use YellowProject\ICNOW\OrderCustomer\CustomerShippingAddress;
use Excel;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function reportEndOfDay(Request $request)
    {
    	$filters = $request->filter_items;
    	$startDate = $filters['start_date'];
    	$endDate = $filters['end_date'];
    	$orderId = $filters['order_id'];
    	$customerId = $filters['customer_id'];
    	$name = $filters['name'];
    	$dtCode = $filters['dt_code'];
    	$dtName = $filters['dt_name'];
    	$miniCode = $filters['mini_code'];
    	$miniName = $filters['mini_name'];
    	$wmCode = $filters['wm_code'];
    	$wmName = $filters['wm_name'];
    	$status = $filters['status'];
    	$datas = \DB::table('fact_icnow_customer_order as co')->select(
	    		'co.id',
	    		'co.order_no as order_id',
	    		'lu.customer_id',
	    		'csa.first_name as name',
	    		\DB::raw('CONCAT(csa.address," ",csa.sub_district," ",csa.sub_district," ",csa.district," ",csa.province," ",csa.post_code) AS shipping_ddress'),
	    		'co.dt_code',
	    		\DB::raw('CONCAT("") as dt_name'),
	    		'co.mini_code',
	    		'co.mini_name',
	    		\DB::raw('CONCAT("") as wm_code'),
	    		\DB::raw('CONCAT("") as wm_name'),
	    		\DB::raw('CONCAT("") as set_data'),
	    		'co.status',
	    		'co.created_at as date'
	    	)
    		->leftjoin('dim_line_user_table as lu','co.line_user_id','=','lu.id')
    		->leftjoin('fact_icnow_customer_shipping_address as csa','lu.address_id','=','csa.id')
    		->orderByDesc('co.created_at')
    		->get();

    	return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportShoppingBehavior(Request $request)
    {
        $datas['shopping_behavior'] = [];
        $datas['shopping_behavior']['header'] = [];
        $datas['shopping_behavior']['header']['all_sessions'] = 0;
        $datas['shopping_behavior']['header']['sessions_with_product_views'] = 0;
        $datas['shopping_behavior']['header']['sessions_with_add_to_cart'] = 0;
        $datas['shopping_behavior']['header']['sessions_with_check_out'] = 0;
        $datas['shopping_behavior']['header']['cancel_by_admin'] = 0;
        $datas['shopping_behavior']['header']['cancel_by_system'] = 0;
        $datas['shopping_behavior']['body'] = [];
        $datas['shopping_behavior']['body']['all_sessions'] = 0;
        $datas['shopping_behavior']['body']['sessions_with_product_views'] = 0;
        $datas['shopping_behavior']['body']['sessions_with_add_to_cart'] = 0;
        $datas['shopping_behavior']['body']['sessions_with_check_out'] = 0;
        $datas['shopping_behavior']['body']['cancel_by_admin'] = 0;
        $datas['shopping_behavior']['body']['cancel_by_system'] = 0;
        $datas['shopping_behavior']['percent'] = [];
        $datas['shopping_behavior']['percent']['all_sessions_percent'] = 0;
        $datas['shopping_behavior']['percent']['sessions_with_product_views_percent'] = 0;
        $datas['shopping_behavior']['percent']['sessions_with_add_to_cart_percent'] = 0;
        $datas['shopping_behavior']['percent']['sessions_with_check_out_percent'] = 0;
        $datas['shopping_behavior']['percent']['cancel_by_admin_percent'] = 0;
        $datas['shopping_behavior']['percent']['cancel_by_system_percent'] = 0;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $logSessions = LogSession::whereDate('created_at','>=',$startDate)->whereDate('created_at','<=',$endDate)->get();
        $orderCustomers = OrderCustomer::whereDate('created_at','>=',$startDate)->whereDate('created_at','<=',$endDate)->get();
        $allSession = $logSessions->count();
        $sessionWithProduct = $logSessions->filter(function ($value, $key) {
            return $value->is_product_view == 1;
        })->count();
        $sessionWithAddToCart = $logSessions->filter(function ($value, $key) {
            return $value->is_add_to_cart == 1;
        })->count();
        $sessionWithCheckOut = $logSessions->filter(function ($value, $key) {
            return $value->is_check_out == 1;
        })->count();
        $orderCancleBySystem = $orderCustomers->filter(function ($value, $key) {
            return $value->status == 'ยกเลิกโดยระบบ';
        })->count();
        $orderCancleByAdmin = $orderCustomers->filter(function ($value, $key) {
            return $value->status == 'ยกเลิกรายการสั่งซื้อโดยมินิ' || $value->status == 'ยกเลิกรายการสั่งซื้อ';
        })->count();

        $datas['shopping_behavior']['header']['all_sessions'] = $allSession;
        $datas['shopping_behavior']['header']['sessions_with_product_views'] = $sessionWithProduct;
        $datas['shopping_behavior']['header']['sessions_with_add_to_cart'] = $sessionWithAddToCart;
        $datas['shopping_behavior']['header']['sessions_with_check_out'] = $sessionWithCheckOut;
        $datas['shopping_behavior']['header']['cancel_by_admin'] = 0;
        $datas['shopping_behavior']['header']['cancel_by_system'] = $orderCancleBySystem;
        $datas['shopping_behavior']['body']['all_sessions'] = $allSession;
        $datas['shopping_behavior']['body']['sessions_with_product_views'] = $sessionWithProduct;
        $datas['shopping_behavior']['body']['sessions_with_add_to_cart'] = $sessionWithAddToCart;
        $datas['shopping_behavior']['body']['sessions_with_check_out'] = $sessionWithCheckOut;
        $datas['shopping_behavior']['body']['cancel_by_admin'] = 0;
        $datas['shopping_behavior']['body']['cancel_by_system'] = $orderCancleBySystem;

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportReturningVisitor(Request $request)
    {
        $datas['returning_visistor'] = [];
        $datas['returning_visistor']['header'] = [];
        $datas['returning_visistor']['header']['all_sessions'] = 0;
        $datas['returning_visistor']['header']['sessions_with_product_views'] = 0;
        $datas['returning_visistor']['header']['sessions_with_add_to_cart'] = 0;
        $datas['returning_visistor']['header']['sessions_with_check_out'] = 0;
        $datas['returning_visistor']['body'] = [];
        $datas['returning_visistor']['body']['all_sessions'] = 0;
        $datas['returning_visistor']['body']['sessions_with_product_views'] = 0;
        $datas['returning_visistor']['body']['sessions_with_add_to_cart'] = 0;
        $datas['returning_visistor']['body']['sessions_with_check_out'] = 0;
        $datas['returning_visistor']['percent'] = [];
        $datas['returning_visistor']['percent']['all_sessions_percent'] = 0;
        $datas['returning_visistor']['percent']['sessions_with_product_views_percent'] = 0;
        $datas['returning_visistor']['percent']['sessions_with_add_to_cart_percent'] = 0;
        $datas['returning_visistor']['percent']['sessions_with_check_out_percent'] = 0;

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $logSessions = LogSession::whereDate('created_at','>=',$startDate)->whereDate('created_at','<=',$endDate)->get();
        $allSession = $logSessions->filter(function ($value, $key) {
            return $value->is_new == 0;
        })->count();
        $sessionWithProduct = $logSessions->filter(function ($value, $key) {
            return $value->is_new == 0 && $value->is_product_view == 1;
        })->count();
        $sessionWithAddToCart = $logSessions->filter(function ($value, $key) {
            return $value->is_new == 0 && $value->is_add_to_cart == 1;
        })->count();
        $sessionWithCheckOut = $logSessions->filter(function ($value, $key) {
            return $value->is_new == 0 && $value->is_check_out == 1;
        })->count();

        $datas['returning_visistor']['header']['all_sessions'] = $allSession;
        $datas['returning_visistor']['header']['sessions_with_product_views'] = $sessionWithProduct;
        $datas['returning_visistor']['header']['sessions_with_add_to_cart'] = $sessionWithAddToCart;
        $datas['returning_visistor']['header']['sessions_with_check_out'] = $sessionWithCheckOut;
        $datas['returning_visistor']['body']['all_sessions'] = $allSession;
        $datas['returning_visistor']['body']['sessions_with_product_views'] = $sessionWithProduct;
        $datas['returning_visistor']['body']['sessions_with_add_to_cart'] = $sessionWithAddToCart;
        $datas['returning_visistor']['body']['sessions_with_check_out'] = $sessionWithCheckOut;


        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportNewVisitor(Request $request)
    {
        $datas['new_visistor'] = [];
        $datas['new_visistor']['header'] = [];
        $datas['new_visistor']['header']['all_sessions'] = 0;
        $datas['new_visistor']['header']['sessions_with_product_views'] = 0;
        $datas['new_visistor']['header']['sessions_with_add_to_cart'] = 0;
        $datas['new_visistor']['header']['sessions_with_check_out'] = 0;
        $datas['new_visistor']['body'] = [];
        $datas['new_visistor']['body']['all_sessions'] = 0;
        $datas['new_visistor']['body']['sessions_with_product_views'] = 0;
        $datas['new_visistor']['body']['sessions_with_add_to_cart'] = 0;
        $datas['new_visistor']['body']['sessions_with_check_out'] = 0;
        $datas['new_visistor']['percent'] = [];
        $datas['new_visistor']['percent']['all_sessions_percent'] = 0;
        $datas['new_visistor']['percent']['sessions_with_product_views_percent'] = 0;
        $datas['new_visistor']['percent']['sessions_with_add_to_cart_percent'] = 0;
        $datas['new_visistor']['percent']['sessions_with_check_out_percent'] = 0;

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $logSessions = LogSession::whereDate('created_at','>=',$startDate)->whereDate('created_at','<=',$endDate)->get();
        $allSession = $logSessions->filter(function ($value, $key) {
            return $value->is_new == 1;
        })->count();
        $sessionWithProduct = $logSessions->filter(function ($value, $key) {
            return $value->is_new == 1 && $value->is_product_view == 1;
        })->count();
        $sessionWithAddToCart = $logSessions->filter(function ($value, $key) {
            return $value->is_new == 1 && $value->is_add_to_cart == 1;
        })->count();
        $sessionWithCheckOut = $logSessions->filter(function ($value, $key) {
            return $value->is_new == 1 && $value->is_check_out == 1;
        })->count();

        $datas['new_visistor']['header']['all_sessions'] = $allSession;
        $datas['new_visistor']['header']['sessions_with_product_views'] = $sessionWithProduct;
        $datas['new_visistor']['header']['sessions_with_add_to_cart'] = $sessionWithAddToCart;
        $datas['new_visistor']['header']['sessions_with_check_out'] = $sessionWithCheckOut;
        $datas['new_visistor']['body']['all_sessions'] = $allSession;
        $datas['new_visistor']['body']['sessions_with_product_views'] = $sessionWithProduct;
        $datas['new_visistor']['body']['sessions_with_add_to_cart'] = $sessionWithAddToCart;
        $datas['new_visistor']['body']['sessions_with_check_out'] = $sessionWithCheckOut;


        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportProductClick(Request $request)
    {
        $datas = [];
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $queryDatas = \DB::table('fact_icnow_log_product_view as pv')
            ->select(
                'pv.line_user_id',
                'pv.product_id',
                'p.product_name'
            )
            ->leftjoin('dim_icnow_product as p','pv.product_id','=','p.id');
        if($startDate != '' && $endDate != ''){
            $queryDatas = $queryDatas->whereDate('pv.created_at','>=',$startDate)->whereDate('pv.created_at','<=',$endDate);
        }
        $groupProductNames = $queryDatas->get()->groupBy('product_name');
        $count = 0;
        foreach ($groupProductNames as $key => $groupProductName) {
            $datas[$count]['product_name'] = $key;
            $datas[$count]['total_click'] = $groupProductName->count();
            $datas[$count]['quantity_click'] = $groupProductName->unique('line_user_id')->count();
            $count++;
        }

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportProductPerformance(Request $request)
    {
        $datas['performace']['header'] = [];
        $datas['performace']['header']['total_order'] = 0;
        $datas['performace']['header']['total_sales'] = 0.00;
        $datas['performace']['header']['average_per_order'] = 0.00;
        $datas['performace']['header']['re_purchase_customer'] = 0.00;
        $datas['performace']['orders_list'] = [];
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $orderCustomers = OrderCustomer::whereDate('created_at','>=',$startDate)->whereDate('created_at','<=',$endDate)->get();
        $queryDatas = \DB::table('fact_icnow_customer_order as co')
            ->select(
                'co.id',
                'co.order_no'
            )
            ->leftjoin('fact_icnow_shopping_cart as sc','co.shopping_cart_id','=','sc.id')
            ->leftjoin('fact_icnow_shopping_cart_item as sci','sci.shopping_cart_id','=','sc.id');
        $orderGroupByOrderId = clone $queryDatas;
        $orderGroupByOrderId = $orderGroupByOrderId
            ->select(
                \DB::raw('sum(sci.price) as total_price')
            )
            ->groupBy('co.order_no')->get();
        $orderGroupBylineUserId = clone $queryDatas;
        $orderGroupBylineUserId = $orderGroupBylineUserId
            ->select(
                \DB::raw('count(co.id) as count_data')
            )
            ->groupBy('co.line_user_id')->get();
        $totalOrder = $orderGroupByOrderId->count();
        $totalSales = $orderGroupByOrderId->sum('total_price');
        $averagePerOrder = $totalSales/$totalOrder;
        $rePurchaseCustomer = $orderGroupBylineUserId->filter(function ($value, $key) {
            return $value->count_data > 1;
        })->count();
        $orderAlls = clone $queryDatas;
        $orderAlls = $queryDatas->select(
            'co.id',
            'co.order_no',
            \DB::raw('sum(sci.quantity) as quantity'),
            \DB::raw('sum(sci.price) as total_price'),
            'co.mini_name',
            'co.created_at as datetime'
        )->groupBy('co.order_no')->get();

        $count = 0;
        foreach ($orderAlls as $key => $orderAll) {
            $datas['performace']['orders_list'][$count]['_no'] = $count+1;
            $datas['performace']['orders_list'][$count]['order_id'] = $orderAll->order_no;
            $datas['performace']['orders_list'][$count]['quantity'] = $orderAll->quantity;
            $datas['performace']['orders_list'][$count]['grand_total'] = $orderAll->total_price;
            $datas['performace']['orders_list'][$count]['dt_name'] = $orderAll->mini_name;
            $datas['performace']['orders_list'][$count]['datetime'] = $orderAll->datetime;
            $count++;
        }

        $datas['performace']['header']['total_order'] = $totalOrder;
        $datas['performace']['header']['total_sales'] = number_format($totalSales,2);
        $datas['performace']['header']['average_per_order'] = number_format($averagePerOrder,2);
        $datas['performace']['header']['re_purchase_customer'] = $rePurchaseCustomer;

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportSummaryOrderExport(Request $request)
    {
        $datas = [];
        $orderCustomers = \DB::table('fact_icnow_customer_order as co')
            ->select(
                'co.*',
                \DB::raw('DATE_FORMAT(co.created_at, "%W") as "order_date_day"'),
                \DB::raw('DATE_FORMAT(co.created_at, "%M") as "order_date_month"'),
                \DB::raw('TIMESTAMPDIFF(MINUTE,co.accept_delivery_date,co.finish_delivery_date) as total_time_taken'),
                'lu.customer_id'
            )
            ->leftjoin('dim_line_user_table as lu','co.line_user_id','=','lu.id')
            ->get();
        $count = 0;
        foreach ($orderCustomers as $key => $orderCustomer) {
            $shoppingCartItems = ShoppingCartItem::where('shopping_cart_id',$orderCustomer->shopping_cart_id)->get();
            $customerShippingAddress = CustomerShippingAddress::find($orderCustomer->address_id);
            foreach ($shoppingCartItems as $key => $shoppingCartItem) {
                $dataShoppingCartDatas = [];
                $dataShoppingCartDatas['product_1'] = "";
                $dataShoppingCartDatas['product__quanlity_1'] = "";
                $dataShoppingCartDatas['product_2'] = "";
                $dataShoppingCartDatas['product__quanlity_2'] = "";
                $dataShoppingCartDatas['product_3'] = "";
                $dataShoppingCartDatas['product__quanlity_3'] = "";
                $dataShoppingCartDatas['product_4'] = "";
                $dataShoppingCartDatas['product__quanlity_4'] = "";
                $dataShoppingCartDatas['product_5'] = "";
                $dataShoppingCartDatas['product__quanlity_5'] = "";
                $dataShoppingCartDatas['product_6'] = "";
                $dataShoppingCartDatas['product__quanlity_6'] = "";
                $setType = "";
                if($shoppingCartItem->section_id == 1){
                    $setType = "Fixed";
                    $shoppingCartItemDetailDiy = ShoppingCartItemDetailDiy::where('shopping_cart_item_id',$shoppingCartItem->id)->first();
                    if($shoppingCartItemDetailDiy){
                        $shoppingCartItemDetailDiyItems = $shoppingCartItemDetailDiy->shoppingCartItemDetailDiyItems;
                    }
                }else{
                    $countShoppingCartItem = 1;
                    $setType = "Custom";
                    $shoppingCartItemDetailPartySets = ShoppingCartItemDetailPartySet::where('shopping_cart_item_id',$shoppingCartItem->id)->get();
                    if($shoppingCartItemDetailPartySets){
                        foreach ($shoppingCartItemDetailPartySets as $key => $shoppingCartItemDetailPartySet) {
                           $shoppingCartItemDetailPartySetItems = $shoppingCartItemDetailPartySet->shoppingCartItemDetailPartySetItems;
                           foreach ($shoppingCartItemDetailPartySetItems as $key => $shoppingCartItemDetailPartySetItem) {
                               $dataShoppingCartDatas['product_'.$countShoppingCartItem] = $shoppingCartItemDetailPartySetItem->item_name;
                               $dataShoppingCartDatas['product__quanlity_'.$countShoppingCartItem] = $shoppingCartItemDetailPartySetItem->item_value;
                               $countShoppingCartItem++;
                           }
                        }
                    }
                }

                $orderDate = Carbon::parse($orderCustomer->created_at);
                $datas[$count]['Order # / ID'] = $orderCustomer->order_no;
                $datas[$count]['Customer ID'] = $orderCustomer->customer_id;
                $datas[$count]['Date'] = $orderDate->format('d/m/Y');
                $datas[$count]['Day of Week'] = $orderCustomer->order_date_day;
                $datas[$count]['Month'] = $orderCustomer->order_date_month;
                $datas[$count]['Year'] = $orderDate->format('Y');
                $datas[$count]['Time'] = $orderDate->format('H:i:s');
                $datas[$count]['Lead Time'] = "";
                $datas[$count]['Set'] = $shoppingCartItem->product_name;
                $datas[$count]['Set Type'] = $setType;
                $datas[$count]['#'] = $shoppingCartItem->quantity;
                $datas[$count]['Product 1'] = $dataShoppingCartDatas['product_1'];
                $datas[$count]['#(Product 1)'] = $dataShoppingCartDatas['product__quanlity_1'];
                $datas[$count]['Product 2'] = $dataShoppingCartDatas['product_2'];
                $datas[$count]['#(Product 2)'] = $dataShoppingCartDatas['product__quanlity_2'];
                $datas[$count]['Product 3'] = $dataShoppingCartDatas['product_3'];
                $datas[$count]['#(Product 3)'] = $dataShoppingCartDatas['product__quanlity_3'];
                $datas[$count]['Product 4'] = $dataShoppingCartDatas['product_4'];
                $datas[$count]['#(Product 4)'] = $dataShoppingCartDatas['product__quanlity_4'];
                $datas[$count]['Product 5'] = $dataShoppingCartDatas['product_5'];
                $datas[$count]['#(Product 5)'] = $dataShoppingCartDatas['product__quanlity_5'];
                $datas[$count]['Product 6'] = $dataShoppingCartDatas['product_6'];
                $datas[$count]['#(Product 6)'] = $dataShoppingCartDatas['product__quanlity_6'];
                $datas[$count]['Total Paid'] = number_format($shoppingCartItem->retial_price,2)." THB";
                $datas[$count]['Province'] = $customerShippingAddress->province;
                $datas[$count]['WM Number'] = $orderCustomer->mini_code;
                $totalTimeTaken = "0 minutes";
                if($orderCustomer->total_time_taken != ""){
                    $totalTimeTaken = $orderCustomer->total_time_taken." minutes";
                }
                $datas[$count]['Order Status'] = $orderCustomer->status;
                $datas[$count]['Total Time Taken (Order to Delivery)'] = $totalTimeTaken;

                $count++;
            }
        }

        $name = "Report Summary Order";

        Excel::create($name, function($excel) use ($datas) {
            $excel->sheet('sheet1', function($sheet) use ($datas)
            {
                $sheet->fromArray($datas);
            });
        })->download('xlsx');
    }
}
