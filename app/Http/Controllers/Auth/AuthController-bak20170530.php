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
        $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();
        $authUser = \Session::get('line-login', '');
        \Session::put('fwd-type', $request->type);
        config(['services.line.redirect' => $lineSettingBusiness->line_url_calback.'/callback']);
        config(['services.line.client_id' => $lineSettingBusiness->channel_id]);
        config(['services.line.client_secret' => $lineSettingBusiness->channel_secret]);
        // if($authUser != ""){
            // return redirect()->action('DashboardController@index');
            // return redirect()->action('DashboardController@index',['mid' => $authUser->mid,'avatar' => $authUser->avatar,'name' => $authUser->name,'type' => $request->type]);
        // }else{
            return Socialite::driver('line')->redirect();
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
        } catch (Exception $e) {
            // \Log::debug($e);
            return redirect()->intended('/login');
        }
 
        $authUser = $this->findOrCreateUser($user);

        \Session::put('line-login', $authUser);
        // return redirect()->action('DashboardController@index');
        // return redirect()->action('DashboardController@index',['mid' => $authUser->mid,'avatar' => $authUser->avatar,'name' => $authUser->name,'type' => $type]);
        return redirect()->action('DashboardController@index',['id' => $authUser->id,'type' => $type]);
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

        if ($authUser = \YellowProject\LineUserProfile::where('mid', $user->id)->first()) {
            return $authUser;
        }

        // dd('aaaaa');

        return \YellowProject\LineUserProfile::create([
            'mid' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'user_type' => 'prospect',
        ]);
    }
}
