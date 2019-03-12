<?php

namespace YellowProject\Http\Controllers\API\v1\Bot;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\Bot\SettingBot;
use YellowProject\ConnectBotNoi;

class SettingBotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settingBot = SettingBot::first();

        return response()->json([
            'datas' => $settingBot,
        ]);
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
        $settingBot = SettingBot::first();
        if($settingBot){
            $settingBot->update($request->all());
        }else{
            SettingBot::create($request->all());
        }

         return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $settingBot = SettingBot::first();

        return response()->json([
            'datas' => $settingBot,
        ]);
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
        $settingBot = SettingBot::find($id);
        $settingBot->update($request->all());

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
        ]);
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

    public function restartBot()
    {
        ConnectBotNoi::restartBot();

        return response()->json([
            'msg_return' => 'restrat bot success',
            'code_return' => 1,
        ]);
    }
}
