<?php

namespace YellowProject\Http\Controllers;

use Illuminate\Http\Request;
use YellowProject\TrackingBc;
use YellowProject\TrackingRecieveBc;
use Jenssegers\Agent\Agent;
use YellowProject\ICNOW\Log\LogSession;

class RecieveTrackingBCController extends Controller
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

    public function recieveCode($code)
    {
        $authUser = \Session::get('line-login', '');
        setcookie('line-user-id', $authUser->id, time() + (86400 * 1), "/");
        $trackingBc = TrackingBc::where('code',$code)->first();
        if(!$trackingBc){
            return view('errors.404');
        }
        if($trackingBc->tracking_source == 'ICNOW'){
            $isNewUser = 1;
            $logSessionAlls = LogSession::where('line_user_id',$authUser->id);
            if($logSessionAlls->count() > 0){
                $isNewUser = 0;
            }
            $logSession = $logSessionAlls->where('is_active',1)->first();
            if($logSession){
                $logSession->update([
                    'is_active' => 0,
                    'is_new' => $isNewUser
                ]);
            }

            LogSession::create([
                'line_user_id' => $authUser->id,
                'is_active' => 1
            ]);
        }
        $agent = new Agent();
        $device = $agent->device();
        $platform = $agent->platform();
        $ip = TrackingBc::getClientIps();
        // $geoLocation = TrackingBc::getGeoLocation($ip);
        // if($geoLocation != ""){
        //     $city = $geoLocation->city;
        //     $lat = $geoLocation->latitude;
        //     $long = $geoLocation->longitude;
        // }else{
            $city = 'Bangkok';
            $lat = null;
            $long = null;
        // }
        TrackingRecieveBc::create([
            'tracking_bc_id'    => $trackingBc->id,
            'line_user_id'      => $authUser->id,
            'ip'                => $ip,
            'device'            => $device,
            'platform'          => $platform,
            'lat'               => $lat,
            'long'              => $long,
            'city'              => $city,
            'tracking_source'   => $trackingBc->tracking_source,
            'tracking_campaign' => $trackingBc->tracking_campaign,
            'tracking_ref'      => $trackingBc->tracking_ref,
            'campaign_id'       => $trackingBc->campaign_id,
        ]);

        return redirect($trackingBc->original_url);
    }

    public function bcCenter($code)
    {
        $trackingBc = TrackingBc::where('code',$code)->first();
        if(!$trackingBc){
            return view('errors.404');
        }
        if($trackingBc->is_line_liff == 1){
            // $url = "line://app/1451346504-27RjQW4b?code=".$code; //ICNOW Real DEV
            $url = "line://app/1542963128-bj74nZqG?code=".$code; //ICNOW TEST DEV
            // $url = "line://app/1451346504-BQ7xXVM6?code=".$code; //ICNOW Real UAT
            // dd($url);
            return redirect()->away($url);
            // return redirect($url);
        }else{
            \Session::put('tracking_bc_code', $code);
            return redirect()->action('Auth\AuthController@redirectToProvider',['type' => 'bc_tracking']);
        }
    }

    public function recieveLiff(Request $request)
    {
        \Session::put('tracking_bc_code', $request->code);
        return redirect()->action('Auth\AuthController@redirectToProvider',['type' => 'bc_tracking']);
    }
}
