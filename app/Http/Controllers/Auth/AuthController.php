<?php

namespace YellowProject\Http\Controllers\Auth;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Socialite;
use Log;
use YellowProject\LineSettingBusiness;
use YellowProject\User;

class AuthController extends Controller
{
    /**
     * Redirect the user to the LINE authentication page.
     *
     * @return Response
     */
    public function redirectToProvider(Request $request)
    {
        // dd('kk');
        $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();
        // $authUser = \Session::get('line-login', '');
        \Session::put('fwd-type', $request->type);
        config(['services.line.redirect' => $lineSettingBusiness->line_url_calback.'/callback']);
        config(['services.line.client_id' => $lineSettingBusiness->channel_id]);
        config(['services.line.client_secret' => $lineSettingBusiness->channel_secret]);
        // if(array_key_exists('line-login', $_COOKIE)){
        //     $type = \Session::get('fwd-type', '');
        //     return redirect()->action('DashboardController@index',['type' => $type]);
        // }else{
            return Socialite::driver('line')->redirect();
        // }
        // if($authUser != ""){
            // return redirect()->action('DashboardController@index');
            // return redirect()->action('DashboardController@index',['mid' => $authUser->mid,'avatar' => $authUser->avatar,'name' => $authUser->name,'type' => $request->type]);
        // }else{
            // return Socialite::driver('line')->redirect();
        // }
    }
 
    /**
     * Obtain the user information from LINE.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request)
    {
        $type = \Session::get('fwd-type', '');
        $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();
        config(['services.line.redirect' => $lineSettingBusiness->line_url_calback.'/callback']);
        config(['services.line.client_id' => $lineSettingBusiness->channel_id]);
        config(['services.line.client_secret' => $lineSettingBusiness->channel_secret]);
        try {
            $user = Socialite::driver('line')->user();
            // \Log::debug($user);
        } catch (\Exception $e) {
            // dd($e);
            // \Log::debug($e);
            // return redirect()->intended('/login');
            abort(500);
        }
 
        $authUser = $this->findOrCreateUser($user);

        \Session::put('line-login', $authUser);
        \Session::put('line-user_id', $authUser->id);
        setcookie('line-login', $authUser, time() + (86400 * 1), "/");
        return redirect()->action('DashboardController@index',['type' => $type]);
        // return redirect()->action('DashboardController@index',['mid' => $authUser->mid,'avatar' => $authUser->avatar,'name' => $authUser->name,'type' => $type]);
    }
 
    /**
     * Logout
     *
     * @return Response
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->intended('/login');
    }
 
    /**
     * Return user if exists; create and return if doesn't
     *
     * @param object $user
     * @return User
     */
    private function findOrCreateUser($user)
    {
    	// dd(Users::where('mid', $user->id)->first());
        // dd('vvvvvvvv');
        // dd($user->phone_number);
        $authUser = \YellowProject\LineUserProfile::where('mid', $user->id)->first();
        $phoneNumber = str_replace("+","",$user->phone_number);
        $phoneNumberOnly = substr($phoneNumber, 2);
        $realPhoneNumber = '0'.$phoneNumberOnly;

        if ($authUser) {
            $authUser->update([
                'name' => $user->name,
                'avatar' => $user->avatar,
                'email' => $user->email,
                'phone_number' => $realPhoneNumber,
            ]);
            return $authUser;
        }

        // dd('aaaaa');
        $customerId = \YellowProject\LineUserProfile::genCustomerNumber();

        return \YellowProject\LineUserProfile::create([
            'customer_id' => $customerId,
            'mid' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'email' => $user->email,
            'phone_number' => $realPhoneNumber,
            'user_type' => 'prospect',
        ]);
    }
}
