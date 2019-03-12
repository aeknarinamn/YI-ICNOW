<?php

namespace YellowProject\Http\Controllers\API\v1\ICNOW\CacheData;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ICNOW\CacheData\CacheData;

class CacheDataController extends Controller
{
    public function saveDataCache(Request $request)
    {
    	$cacheData = CacheData::where('line_user_id',$request->line_user_id)->where('data_id',$request->data_id)->first();
    	if($cacheData){
    		$cacheData->update([
    			'line_user_id' => $request->line_user_id,
    			'data_id' => $request->data_id,
    			'value' => $request->value
    		]);
    	}else{
    		CacheData::create($request->all());
    	}
    }
}
