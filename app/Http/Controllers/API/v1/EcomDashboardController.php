<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\Coupon;
use YellowProject\EcomProductOrderList;
use YellowProject\CouponStaticForm;
use YellowProject\CouponReedeem;
use YellowProject\UserCheckCoupon;
use YellowProject\CouponUser;
use YellowProject\CouponFamilyStaticForm;

class EcomDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function reportCouponChart(Request $request)
    {
        $datas = [];
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $coupons = Coupon::whereNull('is_famliy')->get();
        foreach ($coupons as $key => $coupon) {
            $couponUsers = $coupon->couponUsers;
            $couponUserUsed = CouponUser::where('coupon_id',$coupon->id)->whereBetween('created_at', array($startDate, $endDate))->whereNotNull('reedeem_date');
            // $couponUserReedeems = $coupon->couponUserReedeems;
            $datas[$key]['coupon_name'] = $coupon->name;
            $datas[$key]['redeem'] = $couponUsers->count();
            $datas[$key]['used'] = $couponUserUsed->count();
        }

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportCouponChartFamily(Request $request)
    {
        $datas = [];
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $coupons = Coupon::whereNotNull('is_famliy')->get();
        foreach ($coupons as $key => $coupon) {
            $couponUsers = $coupon->couponUsers;
            $couponUserUsed = CouponUser::where('coupon_id',$coupon->id)->whereBetween('created_at', array($startDate, $endDate))->whereNotNull('reedeem_date');
            // $couponUserReedeems = $coupon->couponUserReedeems;
            $datas[$key]['coupon_name'] = $coupon->name;
            $datas[$key]['redeem'] = $couponUsers->count();
            $datas[$key]['used'] = $couponUserUsed->count();
        }

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportEcommerceChart(Request $request)
    {
        $datas = collect();
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $ecomOrderlists = EcomProductOrderList::whereBetween('created_at', array($startDate, $endDate))->get()->groupBy(function($date) {
            return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
        });
        $count = 0;
        foreach ($ecomOrderlists as $key => $ecomOrderlist) {
            $count++;
            $waitingForpayment = 0;
            $paymentConfirm = 0;
            foreach ($ecomOrderlist as $ecomOrderlistIndex => $ecomOrderlistData) {
                $trackingDetail = $ecomOrderlistData->trackingDetail;
                if($trackingDetail){
                    if($trackingDetail->order_status == 'waiting for payment'){
                        $waitingForpayment++;
                    }

                    if($trackingDetail->order_status == 'payment confirm'){
                        $paymentConfirm++;
                    }
                }
            }
            // $datas[$count] = [array(
            //     'date' => $key,
            //     'order_count' => $ecomOrderlist->count(),
            //     'waiting_payment' => $waitingForpayment,
            //     'payment_confirm' => $paymentConfirm,
            // )];
            $data['date'] = $key;
            $data['order_count'] = $ecomOrderlist->count();
            $data['waiting_payment'] = $waitingForpayment;
            $data['payment_confirm'] = $paymentConfirm;
            $datas->push($data);
            // $datas[$count]['date'] = $key;
            // $datas[$count]['order_count'] = $ecomOrderlist->count();
            // $datas[$count]['waiting_payment'] = $waitingForpayment;
            // $datas[$count]['payment_confirm'] = $paymentConfirm;
        }

        // dd($datas);

        return response()->json([
            'datas' => $datas->toArray(),
        ]);
    }

    public function reportCouponFormChart(Request $request)
    {
        $count = 0;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $staticFormId = $request->static_form_id;
        $datas = [];
        $couponStaticForms = CouponStaticForm::all();
        foreach ($couponStaticForms as $index => $couponStaticForm) {
            $couponStaticFormItems = $couponStaticForm->items->where('is_active',1);
            $couponStaticFormDatas = $couponStaticForm->datas;
            $coupons = $couponStaticForm->coupons;
            foreach ($couponStaticFormDatas->where('created_at','>',$startDate)->where('created_at','<',$endDate)->groupBy('line_user_id') as $key => $couponStaticFormData) {
                $count++;
                $lineUserProfile = $couponStaticFormData->first()->lineUserProfile;
                $datas[$index]['form_data'][$key]['No.'] = $count;
                $datas[$index]['form_data'][$key]['Form Name'] = $couponStaticForm->name;
                $datas[$index]['form_data'][$key]['line_user_id'] = $key;
                $datas[$index]['form_data'][$key]['displayname'] = $lineUserProfile->name;
                // dd($couponStaticFormItems);
                foreach ($couponStaticFormItems as $staticFormItemIndex => $couponStaticFormItem) {
                    // dd($couponStaticFormItem->settingPlaceholder);
                    // $header = "";
                    // $label = $couponStaticFormItem->settingLabel;
                    // $placeHolder = $couponStaticFormItem->settingPlaceholder;
                    // if($label != null || $placeHolder != null){
                    //     if($label != null){
                    //         $header = $label->values;
                    //     }else{
                    //         $header = $placeHolder->values;
                    //     }
                    // }else{
                    //     $header = $couponStaticFormItem->title;
                    // }
                    // if($couponStaticFormItem->el_id == 'google_map_location'){
                    //     $couponStaticFormItem->el_id = 'google_address';
                    //     $header = 'google_address';
                    // }
                    $data =  $couponStaticFormData->where('el_id',$couponStaticFormItem->el_id)->first();
                    if($couponStaticFormItem->el_id == 'txt_tel'){
                        $datas[$index]['form_data'][$key][$couponStaticFormItem->el_id] = ($data)? "'".$data->value : null;
                    }else{
                        $datas[$index]['form_data'][$key][$couponStaticFormItem->el_id] = ($data)? $data->value : null;
                    }
                }
                foreach ($coupons as $couponIndex => $coupon) {
                    $couponReedeem = CouponReedeem::where('coupon_id',$coupon->id)->where('line_user_id',$lineUserProfile->id)->first();
                    $userCheckCode = UserCheckCoupon::where('coupon_id',$coupon->id)->where('line_user_id',$lineUserProfile->id)->first();
                    $couponUser = CouponUser::where('coupon_id',$coupon->id)->where('line_user_id',$lineUserProfile->id)->first();
                    $couponReedeemCode = $coupon->couponReedeemCode;
                    $code = "";
                    $reedeemDate = "";
                    $isReedeem = "";
                    if($couponReedeemCode){
                        if($coupon->is_running_number == 1){
                            $code = $coupon->couponReedeemCode->prefix_code.$coupon->couponReedeemCode->running_code;
                        }else{
                            $code = $coupon->couponReedeemCode->prefix_code;
                        }
                    }

                    if($couponUser){
                        $reedeemDate = $couponUser->reedeem_date;
                        $isReedeem = ($couponUser->flag_status == 'reedeem')? 'yes' : 'no';
                    }
                    // dd($coupon);
                    // $couponFieldIndex = 'Coupon Name'.($couponIndex+1);
                    // dd($couponFieldIndex);
                    $datas[$index]['form_data'][$key]['coupon Name '.($couponIndex+1)] = $coupon->name;
                    $datas[$index]['form_data'][$key]['coupon redeemed '.($couponIndex+1)] = ($userCheckCode)? $userCheckCode->flag_status : null;
                    $datas[$index]['form_data'][$key]['success rate '.($couponIndex+1)] = ($userCheckCode)? $userCheckCode->user_get_coupon_percent : 0;
                    $datas[$index]['form_data'][$key]['set Winning Odds (%) '.($couponIndex+1)] = $coupon->winning_odds;
                    $datas[$index]['form_data'][$key]['maximum Winner '.($couponIndex+1)] = ($coupon->is_winner_nolimit == 1)? 'nolimit' : $coupon->maximum_winner;
                    $datas[$index]['form_data'][$key]['redeemed date '.($couponIndex+1)] = $reedeemDate;
                    $datas[$index]['form_data'][$key]['code '.($couponIndex+1)] = $code;
                    $datas[$index]['form_data'][$key]['code option '.($couponIndex+1)] = ($coupon->is_running_number == 1)? 'running number' : 'prefix name';
                    $datas[$index]['form_data'][$key]['used '.($couponIndex+1)] = $isReedeem;
                    $datas[$index]['form_data'][$key]['coupon start date '.($couponIndex+1)] = $coupon->start_date;
                    $datas[$index]['form_data'][$key]['coupon end date '.($couponIndex+1)] = $coupon->end_date;
                }
                // dd($couponStaticFormData);
            }
        }

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportOverAllChart(Request $request)
    {
        $datas = [];
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $waitingForPayment = EcomProductOrderList::whereBetween('created_at', array($startDate, $endDate))->whereHas('trackingDetail', function ($query) {
                $query->where('order_status','waiting for payment');
            })->count();
        $paymentConfirm = EcomProductOrderList::whereBetween('created_at', array($startDate, $endDate))->whereHas('trackingDetail', function ($query) {
                $query->where('order_status','payment confirm');
            })->count();
        $ship = EcomProductOrderList::whereBetween('created_at', array($startDate, $endDate))->whereHas('trackingDetail', function ($query) {
                $query->where('order_status','ship');
            })->count();
        $cancelByAdmin = EcomProductOrderList::whereBetween('created_at', array($startDate, $endDate))->whereHas('trackingDetail', function ($query) {
                $query->where('order_status','cancel by admin');
            })->count();
        $customerAskByAdmin = EcomProductOrderList::whereBetween('created_at', array($startDate, $endDate))->whereHas('trackingDetail', function ($query) {
                $query->where('order_status','customer ask to cancel');
            })->count();

        $datas['waiting_for_payment'] = $waitingForPayment;
        $datas['payment_confirm'] = $paymentConfirm;
        $datas['ship'] = $ship;
        $datas['cancel_by_admin'] = $cancelByAdmin;
        $datas['customer_ask_to_cancel'] = $customerAskByAdmin;
        // $ecomOrderlists = EcomProductOrderList::whereBetween('created_at', array($startDate, $endDate))->get()->groupBy(function($date) {
        //     return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
        // });

        return response()->json([
            'datas' => [$datas],
        ]);
    }

    public function reportCouponFormChartFamily(Request $request)
    {
        $count = 0;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $staticFormId = $request->static_form_id;
        $datas = [];
        $couponStaticForms = CouponFamilyStaticForm::where('created_at','>',$startDate)->where('created_at','<',$endDate)->get();
        foreach ($couponStaticForms as $index => $couponStaticForm) {
            $coupon = $couponStaticForm->coupon;
            $lineUserProfile = $couponStaticForm->lineUserProfile;
            if($coupon){
                $couponReedeem = CouponReedeem::where('coupon_id',$coupon->id)->where('line_user_id',$lineUserProfile->id)->first();
                $userCheckCode = UserCheckCoupon::where('coupon_id',$coupon->id)->where('line_user_id',$lineUserProfile->id)->first();
                $couponUser = CouponUser::where('coupon_id',$coupon->id)->where('line_user_id',$lineUserProfile->id)->first();
                $couponReedeemCode = $coupon->couponReedeemCode;
                $code = "";
                $reedeemDate = "";
                $isReedeem = "";
                if($couponReedeemCode){
                    if($coupon->is_running_number == 1){
                        $code = $coupon->couponReedeemCode->prefix_code.$coupon->couponReedeemCode->running_code;
                    }else{
                        $code = $coupon->couponReedeemCode->prefix_code;
                    }
                }

                if($couponUser){
                    $reedeemDate = $couponUser->reedeem_date;
                    $isReedeem = ($couponUser->flag_status == 'reedeem')? 'yes' : 'no';
                }
            }

            $count++;
            $datas[$index]['No.'] = $count;
            $datas[$index]['line_user_id'] = $lineUserProfile->id;
            $datas[$index]['displayname'] = $lineUserProfile->name;
            $datas[$index]['first_name'] = $couponStaticForm->first_name;
            $datas[$index]['last_name'] = $couponStaticForm->last_name;
            $datas[$index]['tel'] = $couponStaticForm->tel;
            $datas[$index]['email'] = $couponStaticForm->email;
            $datas[$index]['blueprint_house'] = $couponStaticForm->bluePrintHome->name;
            if($coupon){
                $datas[$index]['coupon Name'] = $coupon->name;
                $datas[$index]['coupon redeemed'] = ($userCheckCode)? $userCheckCode->flag_status : null;
                $datas[$index]['success rate'] = ($userCheckCode)? $userCheckCode->user_get_coupon_percent : 0;
                $datas[$index]['set Winning Odds (%)'] = $coupon->winning_odds;
                $datas[$index]['maximum Winner'] = ($coupon->is_winner_nolimit == 1)? 'nolimit' : $coupon->maximum_winner;
                $datas[$index]['redeemed date'] = $reedeemDate;
                $datas[$index]['code'] = $code;
                $datas[$index]['code option'] = ($coupon->is_running_number == 1)? 'running number' : 'prefix name';
                $datas[$index]['used'] = $isReedeem;
                $datas[$index]['coupon start date'] = $coupon->start_date;
                $datas[$index]['coupon end date'] = $coupon->end_date;
            }else{
                $datas[$index]['coupon Name'] = null;
                $datas[$index]['coupon redeemed'] = null;
                $datas[$index]['success rate'] = null;
                $datas[$index]['set Winning Odds (%)'] = null;
                $datas[$index]['maximum Winner'] = null;
                $datas[$index]['redeemed date'] = null;
                $datas[$index]['code'] = null;
                $datas[$index]['code option'] = null;
                $datas[$index]['used'] = null;
                $datas[$index]['coupon start date'] = null;
                $datas[$index]['coupon end date'] = null;
            }
        }
        return response()->json([
            'datas' => $datas,
        ]);
    }

}
