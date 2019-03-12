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
        // dd(Auth::user());
        // dd($request->all());
    	// Log::debug('Redirect : Success');
        $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();
        // dd(\Session::get('line-login', ''));
        $authUser = \Session::get('line-login', '');
        \Session::put('fwd-type', $request->type);
        // dd($authUser);
        // config(['services.line.redirect' => 'https://yellowdevelop.yellow-idea.com/callback?type='.$request->type]);
        // config(['services.line.redirect' => $lineSettingBusiness->line_url_calback.'/callback?type='.$request->type]);
        config(['services.line.redirect' => $lineSettingBusiness->line_url_calback.'/callback']);
        config(['services.line.client_id' => $lineSettingBusiness->channel_id]);
        config(['services.line.client_secret' => $lineSettingBusiness->channel_secret]);
        if($authUser != ""){
            return redirect()->action('DashboardController@index',['mid' => $authUser->mid,'avatar' => $authUser->avatar,'name' => $authUser->name,'type' => $request->type]);
        }else{
            return Socialite::driver('line')->redirect();
        }
    }
 
    /**
     * Obtain the user information from LINE.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request)
    {
        // dd($request->all());
        // $type = $request->type;
        $type = \Session::get('fwd-type', '');
        // dd($type);
        $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();
        // config(['services.line.redirect' => 'https://yellowdevelop.yellow-idea.com/callback?type='.$type]);
        config(['services.line.redirect' => $lineSettingBusiness->line_url_calback.'/callback']);
        config(['services.line.client_id' => $lineSettingBusiness->channel_id]);
        config(['services.line.client_secret' => $lineSettingBusiness->channel_secret]);
    	// Log::debug('save data : success ');
        try {
            $user = Socialite::driver('line')->user();
            // dd($user);
        } catch (Exception $e) {
            return redirect()->intended('/login');
        }
 
        $authUser = $this->findOrCreateUser($user);

        \Session::put('line-login', $authUser);
        // $user = User::first();
        // dd($authUser);
        // Auth::login($user, true);
        // Auth::loginUsingId("dim_line_user_table", 1);
        // Auth::guard('dim_line_user_table')->attempt(['mid' => $authUser->mid,'password' => 'xxxxxx']);
        // \Auth::attempt("line", ['mid' => $authUser->mid]);
        // dd(auth()->guard('dim_line_user_table')->user());
        // dd(Auth::guard('line')->user());

        // return redirect()->intended('dashboard');
        return redirect()->action('DashboardController@index',['mid' => $authUser->mid,'avatar' => $authUser->avatar,'name' => $authUser->name,'type' => $type]);
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
