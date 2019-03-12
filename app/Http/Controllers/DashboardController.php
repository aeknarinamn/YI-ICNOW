<?php

namespace YellowProject\Http\Controllers;

use Illuminate\Http\Request;
use YellowProject\LineUserProfile;

class DashboardController extends Controller
{
 
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }
 
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $lineUser = $request->all();
        $type = $request->type;
        // dd($type);
        // $type = \Session::get('fwd-type', '');
        // $type = (isset($request->type))? $request->type : \Session::get('fwd-type', '');
        $lineUser = \Session::get('line-login', '');
        if($lineUser == "" && !array_key_exists('line-login', $_COOKIE)){
            return view('errors.404');
        }
        $lineLogin = json_decode($_COOKIE['line-login']);
        // $lineUserProfile = LineUserProfile::where('mid',$lineLogin->mid)->first();
        \Session::put('line-login', $lineLogin);
        if($type == 'leadform'){
            return redirect()->action('ProfillingController@show',['route' => 'preference']);
        }
        
        if($type == 'bc_tracking'){
           $code = \Session::get('tracking_bc_code', '');
           \Session::put('tracking_bc_code', '');
           return redirect()->action('RecieveTrackingBCController@recieveCode',['code' => $code]);
           // dd($code);
        }

        // if($type == 'dt_code'){
        //    $code = \Session::get('dt_code', '');
        //    \Session::put('dt_code', '');
        //    return redirect()->action('RecieveDTManagementController@recieveCode',['code' => $code]);
        //    // dd($code);
        // }
    }
}
