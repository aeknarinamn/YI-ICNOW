<?php

namespace YellowProject\Http\Controllers\ICNOW\View;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\LineUserProfile;
use YellowProject\ICNOW\AdminUser\AdminUser;
use YellowProject\User;

class AdminUserController extends Controller
{
    public function adminUserPage()
    {
    	if(!array_key_exists('line-user-id', $_COOKIE)){
            abort(404);
        }
        $lineUserId = $_COOKIE['line-user-id'];
        $adminUser = AdminUser::where('line_user_id',$lineUserId)->where('is_user',1)->first();
        if($adminUser){
        	abort(404);
        }
        $lineUserProfile = LineUserProfile::find($lineUserId);

    	return view('icnow.admin-register.index')
    		->with('lineUserProfile',$lineUserProfile);
    }

    public function adminUserStore(Request $request)
    {
    	$isUser = 0;
    	$email = $request->email;
    	$lineUserId = $request->line_user_id;
    	$user = User::where('email',$email)->first();
    	if($user){
    		$isUser = 1;
    	}

    	AdminUser::create([
    		'line_user_id' => $lineUserId,
    		'email' => $email,
    		'is_user' => $isUser
    	]);

    	return redirect('/admin-register-thank');
    }

    public function thank()
    {
    	return view('icnow.admin-register.thank');
    }
}
