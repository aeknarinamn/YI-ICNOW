<?php

namespace YellowProject\Http\Controllers;

use Illuminate\Http\Request;
use YellowProject\Profilling;
use YellowProject\ProfillingAction;
use YellowProject\ProfillingActionCss;
use YellowProject\ProfillingActionParentCss;
use YellowProject\ProfillingActionSetting;
use YellowProject\ProfillingActionLabelCss;
use YellowProject\LineUserProfile;
use YellowProject\Subscriber;
use YellowProject\SubscriberItem;
use YellowProject\SubscriberLine;
use YellowProject\FWDToken;
use YellowProject\FamilyConnection;
use YellowProject\Field;
use YellowProject\GeneralFunction\BannLaeSuan;
use Carbon\Carbon;

class ProfillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $profilling = Profilling::find(7);
        // $profillingActions = $profilling->ProfillingActions;
        // foreach ($profillingActions as $key => $profillingAction) {
        //     $profillingActionCss = $profillingAction->css;
        //     $profillingActionLabelCss = $profillingAction->labelCss;
        //     $profillingActionParentCss = $profillingAction->parentCss;
        //     $profillingActionSetting = $profillingAction->settings;
        //     $fields = $profillingAction->field;
        //     if($fields){
        //         foreach ($fields as $key => $field) {
        //             $fieldEnum = $field->fieldItems;
        //         }
        //     }
        // }
        // return view('profilling.index')
        //     ->with('datas',json_encode($profilling));
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
        if($request->subscriber_id != 0 && $request->subscriber_id != ""){
            $authUser = \Session::get('line-login', '');
            $profilling = Profilling::find($request->profilling_id);
            // $profilling = Profilling::where('subscriber_id',$request->subscriber_id)->first();
            $lineUserProfile = LineUserProfile::find($authUser->id);
            $subscriberId = $request->subscriber_id;
            $subscriber = Subscriber::find($subscriberId);
            $susbcriberLine = SubscriberLine::where('subscriber_id',$subscriberId)->where('line_user_id',$lineUserProfile->id)->first();
            if($susbcriberLine){
                $susbcriberLine->update([
                    'updated_at' => \Carbon\Carbon::now(),
                ]);
            }else{
                $susbcriberLine = SubscriberLine::create([
                    'subscriber_id' => $subscriberId,
                    'line_user_id' => $lineUserProfile->id
                ]);
            }
            if(isset($request->field_items)){
                foreach ($request->field_items as $filedId => $fieldValue) {
                    $field = Field::find($filedId);
                    if($field->is_master_subscriber_update == 1){
                        $fieldMasterSubscriber = Field::find($field->mapping_master_field);
                        $subscriberLineMasterSubscriber = SubscriberLine::where('subscriber_id',$fieldMasterSubscriber->subscriber_id)->where('line_user_id',$lineUserProfile->id)->first();
                        if(!$subscriberLineMasterSubscriber){
                          $subscriberLineMasterSubscriber = SubscriberLine::create([
                            'subscriber_id' => $fieldMasterSubscriber->subscriber_id,
                            'line_user_id' => $lineUserProfile->id
                          ]);
                        }

                        $subscriberLineMasterSubscriberItem = SubscriberItem::where('subscriber_line_id',$subscriberLineMasterSubscriber->id)->where('field_id',$fieldMasterSubscriber->id)->first();
                        if($subscriberLineMasterSubscriberItem){
                          $subscriberLineMasterSubscriberItem->update([
                            'value' => $fieldValue
                          ]);
                        }else{
                          SubscriberItem::create([
                            'subscriber_line_id' => $subscriberLineMasterSubscriber->id,
                            'field_id' => $fieldMasterSubscriber->id,
                            'value' => $fieldValue
                          ]);
                        }
                    }
                    $subscriberItem = SubscriberItem::where('subscriber_line_id',$susbcriberLine->id)->where('field_id',$filedId)->first();
                    if($subscriberItem){
                        $subscriberItem->update([
                            'value' => $fieldValue,
                        ]);
                    }else{
                        SubscriberItem::create([
                            'subscriber_line_id' => $susbcriberLine->id,
                            'field_id' => $filedId,
                            'value' => $fieldValue
                        ]);
                    }
                }

                if($subscriber->name == 'BannLaeSuan'){
                    $datas = BannLaeSuan::convertData($request->field_items,$lineUserProfile);
                    // dd($datas);
                    FamilyConnection::bssServiceRegisterLineMember($datas);
                }
            }
            // dd($profilling);
            // return redirect("/ai/".$profilling->redirect_route);
            return redirect($profilling->redirect_route);
        }else{
            $profilling = Profilling::find($request->profilling_id);
            return redirect($profilling->redirect_route);
        }
        // return response()->json([
        //     'msg_return' => 'บันทึกสำเร็จ',
        //     'code_return' => 1,
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($route)
    {
        // $authUser = \Session::put('line-login', '');
        $authUser = \Session::get('line-login', '');
        // if($authUser != ''){
            if($authUser == '' && $route != 'preference'){
                return view('errors.404');
            }
            if($authUser == '' && $route == 'preference'){
                return redirect()->action('Auth\AuthController@redirectToProvider',['type' => 'leadform']);
            }
            $profilling = Profilling::where('route',$route)->first();
            // dd($profilling);
            if($profilling){
                if($profilling->is_route_expire != "" && $profilling->is_route_expire == 1){
                    $dateNow = Carbon::now()->format('Y-m-d H:i');
                    if($profilling->route_expire_date < $dateNow){
                        return redirect($profilling->route_expire_url);
                    }
                }
                $fwdToken = FWDToken::where('name','Hello Soda')->first();
                \Session::put('hello_soda_in_token', $fwdToken->token);
                $checkSubscriberData = Profilling::checkSubscriberData($authUser->id,$profilling);
                if($profilling->is_use_hellosoda == 1){
                    $profilling->redirect_route = '/fwdconnect?url='.$profilling->redirect_route;
                    // return redirect($profilling->redirect_route);
                }
                if($checkSubscriberData == 1){
                    return redirect($profilling->redirect_route);
                }else{
                    if($profilling->subscriber_id != 0){
                        $subscriberLine = SubscriberLine::where('subscriber_id',$profilling->subscriber_id)->where('line_user_id',$authUser->id)->first();
                        if($subscriberLine){
                            $susbcriberDatas = $subscriberLine->subscriberItems;
                        }else{
                            $susbcriberDatas = collect([]);
                        }
                    }else{
                        $susbcriberDatas = collect([]);
                    }
                    // $susbcriberDatas = $profilling->subscriber_id;

                    $profillingActions = $profilling->ProfillingActions;
                    foreach ($profillingActions as $key => $profillingAction) {
                        $profillingActionCss = $profillingAction->css;
                        $profillingActionLabelCss = $profillingAction->labelCss;
                        $profillingActionParentCss = $profillingAction->parentCss;
                        $profillingActionSetting = $profillingAction->settings;
                        $fields = $profillingAction->field;
                        if($fields!= "" && ($fields->type == 'enum_dropdown' || $fields->type == 'enum_radio')){
                            $fieldEnum = $fields->fieldItems;
                        }
                    }
                    // dd($profillingActions);
                    return view('profilling.index')
                    ->with('subscriberDatas',json_encode($susbcriberDatas))
                    ->with('datas',json_encode($profilling));
                }
            }else{
                return view('errors.404');
            }
        // }else{
        //     return view('errors.404');
        // }
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
}
