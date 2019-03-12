<?php

namespace YellowProject\Http\Controllers\ICNOW\View;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\LineUserProfile;

class HowToBuyController extends Controller
{
    public function howToBuyPage()
    {
    	if(!array_key_exists('line-user-id', $_COOKIE)){
            abort(404);
        }
    	return view('icnow.how-to-buy.index');
    }
}
