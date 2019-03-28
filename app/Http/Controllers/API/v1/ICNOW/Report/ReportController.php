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
	    		\DB::raw('sum(isci.retial_price*isci.quantity) as set_data'),
	    		'co.status',
	    		'co.created_at as date'
	    	)
    		->leftjoin('dim_line_user_table as lu','co.line_user_id','=','lu.id')
            ->leftjoin('fact_icnow_customer_shipping_address as csa','lu.address_id','=','csa.id')
            ->leftjoin('fact_icnow_shopping_cart as isc','co.shopping_cart_id','=','isc.id')
    		->leftjoin('fact_icnow_shopping_cart_item as isci','isc.id','=','isci.shopping_cart_id');
        if($startDate != "" && $endDate != ""){
            $datas = $datas->where('co.created_at','>=',$startDate)->where('co.created_at','<=',$endDate);
        }
        if($orderId != ""){
            $datas = $datas->where('co.order_no',$orderId);
        }
        if($customerId != ""){
            $datas = $datas->where('lu.customer_id',$customerId);
        }
        if($dtCode != ""){
            $datas = $datas->where('co.dt_code',$dtCode);
        }
        // if($dtName != ""){
        //     $datas = $datas->where('co.dt_code',$dtName);
        // }
        if($miniCode != ""){
            $datas = $datas->where('co.mini_code',$miniCode);
        }
        if($miniName != ""){
            $datas = $datas->where('co.mini_name',$miniName);
        }
        if($status != ""){
            $datas = $datas->where('co.status',$status);
        }

    	$datas = $datas->orderByDesc('co.created_at')
            ->groupBy('co.order_no')
    		->get();

    	return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportEndOfDayExport(Request $request)
    {
        $datas = \DB::table('fact_icnow_customer_order as co')->select(
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
                \DB::raw('sum(isci.retial_price*isci.quantity) as set_data'),
                'co.status',
                'co.created_at as date'
            )
            ->leftjoin('dim_line_user_table as lu','co.line_user_id','=','lu.id')
            ->leftjoin('fact_icnow_customer_shipping_address as csa','lu.address_id','=','csa.id')
            ->leftjoin('fact_icnow_shopping_cart as isc','co.shopping_cart_id','=','isc.id')
            ->leftjoin('fact_icnow_shopping_cart_item as isci','isc.id','=','isci.shopping_cart_id');
        $datas = $datas->orderByDesc('co.created_at')
            ->groupBy('co.order_no')
            ->get()->toArray();

        $datas = collect($datas)->map(function($x){ return (array) $x; })->toArray();

        Excel::create("END OF DAY REPORT", function($excel) use ($datas) {
            $excel->sheet('sheet1', function($sheet) use ($datas)
            {
                $sheet->fromArray($datas);
            });
        })->download('xlsx');
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

        $percentAllSession = ($allSession > 0)? ($allSession * 100)/$allSession : 0;
        $percentProductView = ($allSession > 0)? ($sessionWithProduct * 100)/$allSession : 0;
        $percentAddToCart = ($allSession > 0)? ($sessionWithAddToCart * 100)/$allSession : 0;
        $percentCheckOut = ($allSession > 0)? ($sessionWithCheckOut * 100)/$allSession : 0;
        $percentCancelByAdmin = ($allSession > 0)? ($orderCancleByAdmin * 100)/$allSession : 0;
        $percentCancelByMini = ($allSession > 0)? ($orderCancleBySystem * 100)/$allSession : 0;

        $datas['shopping_behavior']['header']['all_sessions'] = $allSession;
        $datas['shopping_behavior']['header']['sessions_with_product_views'] = $sessionWithProduct;
        $datas['shopping_behavior']['header']['sessions_with_add_to_cart'] = $sessionWithAddToCart;
        $datas['shopping_behavior']['header']['sessions_with_check_out'] = $sessionWithCheckOut;
        $datas['shopping_behavior']['header']['cancel_by_admin'] = $orderCancleByAdmin;
        $datas['shopping_behavior']['header']['cancel_by_system'] = $orderCancleBySystem;
        $datas['shopping_behavior']['body']['all_sessions'] = $percentAllSession;
        $datas['shopping_behavior']['body']['sessions_with_product_views'] = $percentProductView;
        $datas['shopping_behavior']['body']['sessions_with_add_to_cart'] = $percentAddToCart;
        $datas['shopping_behavior']['body']['sessions_with_check_out'] = $percentCheckOut;
        $datas['shopping_behavior']['body']['cancel_by_admin'] = $percentCancelByAdmin;
        $datas['shopping_behavior']['body']['cancel_by_system'] = $percentCancelByMini;
        $datas['shopping_behavior']['percent']['all_sessions_percent'] = $percentAllSession;
        $datas['shopping_behavior']['percent']['sessions_with_product_views_percent'] = $percentProductView;
        $datas['shopping_behavior']['percent']['sessions_with_add_to_cart_percent'] = $percentAddToCart;
        $datas['shopping_behavior']['percent']['sessions_with_check_out_percent'] = $percentCheckOut;
        $datas['shopping_behavior']['percent']['cancel_by_admin_percent'] = $percentCancelByAdmin;
        $datas['shopping_behavior']['percent']['cancel_by_system_percent'] = $percentCancelByMini;

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportShoppingBehaviorExport(Request $request)
    {
        $datas = [];
        
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

        $percentAllSession = ($allSession > 0)? ($allSession * 100)/$allSession : 0;
        $percentProductView = ($allSession > 0)? ($sessionWithProduct * 100)/$allSession : 0;
        $percentAddToCart = ($allSession > 0)? ($sessionWithAddToCart * 100)/$allSession : 0;
        $percentCheckOut = ($allSession > 0)? ($sessionWithCheckOut * 100)/$allSession : 0;
        $percentCancelByAdmin = ($allSession > 0)? ($orderCancleByAdmin * 100)/$allSession : 0;
        $percentCancelByMini = ($allSession > 0)? ($orderCancleBySystem * 100)/$allSession : 0;

        $datas[0]['All_Sessions'] = $allSession;
        $datas[0]['Sessions_With_Product_Views'] = $sessionWithProduct;
        $datas[0]['Sessions_With_Add_To_Cart'] = $sessionWithAddToCart;
        $datas[0]['Sessions_With_Check_Out'] = $sessionWithCheckOut;
        $datas[0]['Cancel_By_Admin'] = $orderCancleByAdmin;
        $datas[0]['Cancel_By_System'] = $orderCancleBySystem;
        $datas[0]['All_Sessions_Percent'] = $allSession;
        $datas[0]['Sessions_With_Product_Views_Percent'] = $percentProductView;
        $datas[0]['Sessions_With_Add_To_Cart_Percent'] = $percentAddToCart;
        $datas[0]['Sessions_With_Check_Out_Percent'] = $percentCheckOut;
        $datas[0]['Cancel_By_Admin_Percent'] = $percentCancelByAdmin;
        $datas[0]['Cancel_By_System_Percent'] = $percentCancelByMini;


        Excel::create("SHOPPING BEHAVIOR REPORT", function($excel) use ($datas) {
            $excel->sheet('sheet1', function($sheet) use ($datas)
            {
                $sheet->fromArray($datas);
            });
        })->download('xlsx');

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

        $percentAllSession = ($allSession > 0)? ($allSession * 100)/$allSession : 0;
        $percentProductView = ($allSession > 0)? ($sessionWithProduct * 100)/$allSession : 0;
        $percentAddToCart = ($allSession > 0)? ($sessionWithAddToCart * 100)/$allSession : 0;
        $percentCheckOut = ($allSession > 0)? ($sessionWithCheckOut * 100)/$allSession : 0;

        $datas['returning_visistor']['header']['all_sessions'] = $allSession;
        $datas['returning_visistor']['header']['sessions_with_product_views'] = $sessionWithProduct;
        $datas['returning_visistor']['header']['sessions_with_add_to_cart'] = $sessionWithAddToCart;
        $datas['returning_visistor']['header']['sessions_with_check_out'] = $sessionWithCheckOut;
        $datas['returning_visistor']['body']['all_sessions'] = $allSession;
        $datas['returning_visistor']['body']['sessions_with_product_views'] = $sessionWithProduct;
        $datas['returning_visistor']['body']['sessions_with_add_to_cart'] = $sessionWithAddToCart;
        $datas['returning_visistor']['body']['sessions_with_check_out'] = $sessionWithCheckOut;
        $datas['returning_visistor']['percent']['all_sessions_percent'] = $percentAllSession;
        $datas['returning_visistor']['percent']['sessions_with_product_views_percent'] = $percentProductView;
        $datas['returning_visistor']['percent']['sessions_with_add_to_cart_percent'] = $percentAddToCart;
        $datas['returning_visistor']['percent']['sessions_with_check_out_percent'] = $percentCheckOut;


        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportReturningVisitorExport(Request $request)
    {
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

        $percentAllSession = ($allSession * 100)/$allSession;
        $percentProductView = ($sessionWithProduct * 100)/$allSession;
        $percentAddToCart = ($sessionWithAddToCart * 100)/$allSession;
        $percentCheckOut = ($sessionWithCheckOut * 100)/$allSession;

        $datas[0]['User_Type'] = "Returning Visistor";
        $datas[0]['All_Sessions'] = $allSession;
        $datas[0]['Sessions_with_Product_Views'] = $sessionWithProduct;
        $datas[0]['Sessions_with_Product_Views_Percent'] = $percentProductView;
        $datas[0]['Sessions_with_Add_To_Cart'] = $sessionWithAddToCart;
        $datas[0]['Sessions_with_Add_To_Cart_Percent'] = $percentAddToCart;
        $datas[0]['Sessions_with_Check_Out'] = $sessionWithCheckOut;
        $datas[0]['Sessions_with_Check_Out_Percent'] = $percentCheckOut;

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

        $percentAllSession = ($allSession > 0)? ($allSession * 100)/$allSession : 0;
        $percentProductView = ($allSession > 0)? ($sessionWithProduct * 100)/$allSession : 0;
        $percentAddToCart = ($allSession > 0)? ($sessionWithAddToCart * 100)/$allSession : 0;
        $percentCheckOut = ($allSession > 0)? ($sessionWithCheckOut * 100)/$allSession : 0;

        $datas[1]['User_Type'] = "New Visistor";
        $datas[1]['All_Sessions'] = $allSession;
        $datas[1]['Sessions_with_Product_Views'] = $sessionWithProduct;
        $datas[1]['Sessions_with_Product_Views_Percent'] = $percentProductView;
        $datas[1]['Sessions_with_Add_To_Cart'] = $sessionWithAddToCart;
        $datas[1]['Sessions_with_Add_To_Cart_Percent'] = $percentAddToCart;
        $datas[1]['Sessions_with_Check_Out'] = $sessionWithCheckOut;
        $datas[1]['Sessions_with_Check_Out_Percent'] = $percentCheckOut;

        Excel::create("RETURNING VS NEW VISISTOR", function($excel) use ($datas) {
            $excel->sheet('sheet1', function($sheet) use ($datas)
            {
                $sheet->fromArray($datas);
            });
        })->download('xlsx');
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

        $percentAllSession = ($allSession > 0)? ($allSession * 100)/$allSession : 0;
        $percentProductView = ($allSession > 0)? ($sessionWithProduct * 100)/$allSession : 0;
        $percentAddToCart = ($allSession > 0)? ($sessionWithAddToCart * 100)/$allSession : 0;
        $percentCheckOut = ($allSession > 0)? ($sessionWithCheckOut * 100)/$allSession : 0;

        $datas['new_visistor']['header']['all_sessions'] = $allSession;
        $datas['new_visistor']['header']['sessions_with_product_views'] = $sessionWithProduct;
        $datas['new_visistor']['header']['sessions_with_add_to_cart'] = $sessionWithAddToCart;
        $datas['new_visistor']['header']['sessions_with_check_out'] = $sessionWithCheckOut;
        $datas['new_visistor']['body']['all_sessions'] = $allSession;
        $datas['new_visistor']['body']['sessions_with_product_views'] = $sessionWithProduct;
        $datas['new_visistor']['body']['sessions_with_add_to_cart'] = $sessionWithAddToCart;
        $datas['new_visistor']['body']['sessions_with_check_out'] = $sessionWithCheckOut;
        $datas['new_visistor']['percent']['all_sessions_percent'] = $percentAllSession;
        $datas['new_visistor']['percent']['sessions_with_product_views_percent'] = $percentProductView;
        $datas['new_visistor']['percent']['sessions_with_add_to_cart_percent'] = $percentAddToCart;
        $datas['new_visistor']['percent']['sessions_with_check_out_percent'] = $percentCheckOut;


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

    public function reportProductClickExport(Request $request)
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

        Excel::create("PRODUCT CLICK", function($excel) use ($datas) {
            $excel->sheet('sheet1', function($sheet) use ($datas)
            {
                $sheet->fromArray($datas);
            });
        })->download('xlsx');
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
        if($startDate != "" && $endDate != ""){
            $queryDatas = $queryDatas->where('co.created_at','>=',$startDate)->where('co.created_at','<=',$endDate);
        }
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
        $averagePerOrder = ($totalOrder > 0)? $totalSales/$totalOrder : 0;
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
            'co.created_at as datetime',
            \DB::raw('max(co.created_at) as max_created_at')
        )->groupBy('co.order_no')->get()->sortByDesc('max_created_at')->take(5);

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

    public function reportProductPerformanceExport(Request $request)
    {
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
        if($startDate != "" && $endDate != ""){
            $queryDatas = $queryDatas->where('co.created_at','>=',$startDate)->where('co.created_at','<=',$endDate);
        }
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
        $averagePerOrder = ($totalOrder > 0)? $totalSales/$totalOrder : 0;
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
            'co.created_at as datetime',
            \DB::raw('max(co.created_at) as max_created_at')
        )->groupBy('co.order_no')->get()->sortByDesc('max_created_at')->take(5);

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

        $dataExports = [];
        $dataExports[0]['total_order'] = $totalOrder;
        $dataExports[0]['total_sales'] = number_format($totalSales,2);
        $dataExports[0]['average_per_order'] = number_format($averagePerOrder,2);
        $dataExports[0]['re_purchase_customer'] = $rePurchaseCustomer;

        Excel::create("PRODUCT PERFORMANCE", function($excel) use ($dataExports) {
            $excel->sheet('sheet1', function($sheet) use ($dataExports)
            {
                $sheet->fromArray($dataExports);
            });
        })->download('xlsx');
    }

    public function reportSummaryOrderExport(Request $request)
    {
        $datas = [];
        $orderCustomers = \DB::table('fact_icnow_customer_order as co')
            ->select(
                'co.*',
                'im.walls_code',
                \DB::raw('DATE_FORMAT(co.created_at, "%W") as "order_date_day"'),
                \DB::raw('DATE_FORMAT(co.created_at, "%M") as "order_date_month"'),
                \DB::raw('TIMESTAMPDIFF(MINUTE,co.accept_delivery_date,co.finish_delivery_date) as total_time_taken'),
                'lu.customer_id'
            )
            ->leftjoin('dim_line_user_table as lu','co.line_user_id','=','lu.id')
            ->leftjoin('dim_icnow_mini as im','im.id','=','co.mini_id')
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
                    $setType = "Budget";
                    $shoppingCartItemDetailDiy = ShoppingCartItemDetailDiy::where('shopping_cart_item_id',$shoppingCartItem->id)->first();
                    if($shoppingCartItemDetailDiy){
                        $shoppingCartItemDetailDiyItems = $shoppingCartItemDetailDiy->shoppingCartItemDetailDiyItems;
                    }
                }else if($shoppingCartItem->section_id == 3){
                    $setType = "Custom";
                }else{
                    $setType = "Fixed";
                    $countShoppingCartItem = 1;
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
                $datas[$count]['วันที่ลูกค้ากดยืนยัน'] = $orderCustomer->customer_submit_shopping_cart_date;
                $datas[$count]['เวลาที่ลูกค้ากดยืนยัน'] = $orderCustomer->customer_submit_shopping_cart_time;
                $datas[$count]['วันที่ลูกค้าเลือกให้จัดส่ง'] = $orderCustomer->customer_submit_order_date;
                $datas[$count]['เวลาที่ลูกค้าเลือกให้จัดส่ง'] = $orderCustomer->customer_submit_order_time;
                $datas[$count]['วันที่Mini กดยืนยันการจัดส่ง'] = $orderCustomer->mini_confirm_delivery_date;
                $datas[$count]['เวลาที่Mini กดยืนยันการจัดส่ง'] = $orderCustomer->mini_confirm_delivery_time;
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
                $datas[$count]['Province'] = ($customerShippingAddress)? $customerShippingAddress->province : "N/A";
                $datas[$count]['Mini Number'] = $orderCustomer->mini_code;
                $datas[$count]['WM Number'] = $orderCustomer->walls_code;
                $totalTimeTaken = "0 minutes";
                if($orderCustomer->total_time_taken != ""){
                    $totalTimeTaken = $orderCustomer->total_time_taken." minutes";
                }
                $datas[$count]['Order Status'] = $orderCustomer->status;
                $datas[$count]['Quality Score'] = $orderCustomer->rating_1;
                $datas[$count]['Speed Score'] = $orderCustomer->rating_2;
                $datas[$count]['Manner Score'] = $orderCustomer->rating_3;
                $datas[$count]['Overall Score'] = $orderCustomer->rating_4;

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
