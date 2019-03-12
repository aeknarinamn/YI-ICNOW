<?php

namespace YellowProject\Http\Controllers\API\v1\GeneralController;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\LineSettingBusiness;
use YellowProject\LineWebHooks;

class LineConnectionController extends Controller
{
    public function replyLine(Request $request)
    {
        $datas = collect();
        $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();
        $data = collect($request->all());
        $datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);
        $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/reply');
        $datas->put('data', $data->toJson());
        $sent = LineWebHooks::sent($datas);
        return response()->json($sent);
    }

    public function multicastLine(Request $request)
    {
        $datas = collect();
        $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();
        $data = collect($request->all());
        $datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);
        $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/multicast');
        $datas->put('data', $data->toJson());
        $sent = LineWebHooks::sent($datas);
        return response()->json($sent);
    }

    public function pushLine(Request $request)
    {
        // dd($request->all());
        $datas = collect();
        $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();
        $data = collect($request->all());
        $datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);
        $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/push');
        $datas->put('data', $data->toJson());
        $sent = LineWebHooks::sent($datas);
        return response()->json($sent);
    }
}
