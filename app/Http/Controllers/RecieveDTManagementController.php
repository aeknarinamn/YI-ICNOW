<?php

namespace YellowProject\Http\Controllers;

use Illuminate\Http\Request;
use YellowProject\Ecommerce\DTManagement\DTManagement;
use YellowProject\Ecommerce\DTManagement\DTManagementRegisterData;

class RecieveDTManagementController extends Controller
{
    public function recieveCode($code)
    {
        $authUser = \Session::get('line-login', '');
        $DTManagement = DTManagement::where('dt_code_login',$code)->first();
        if(!$DTManagement){
            return view('errors.404');
        }
        
        $DTManagementRegisterData = DTManagementRegisterData::where('line_user_id',$authUser->id)->first();

        if($DTManagementRegisterData){
        	return redirect('/login');
        }else{
        	return view('auth.dt-register')
        		->with('dt_code',$code)
        		->with('line_user_id',$authUser->id);
        }
    }
}
