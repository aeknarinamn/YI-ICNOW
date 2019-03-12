<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\RecieveMessage;
use Excel;
use Carbon\Carbon;

class ReportBotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $datas = collect();
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $recieveMessageDatas = [];
        $recieveMessages = RecieveMessage::whereBetween('created_at', array($startDate, $endDate))->select('*', \DB::raw('count(*) as countKeyword'), \DB::raw('max(created_at) as created_at'))
                 ->groupBy('keyword','bot_reply','mid')->orderByDesc('created_at')->get();
        foreach ($recieveMessages as $key => $recieveMessage) {
            $recieveMessageDatas[$key]['line_display_name'] = $recieveMessage->lineUserProfile->name;
            $recieveMessageDatas[$key]['keyword'] = $recieveMessage->keyword;
            $recieveMessageDatas[$key]['bot_conf'] = $recieveMessage->bot_conf;
            $recieveMessageDatas[$key]['bot_reply'] = ($recieveMessage->bot_reply != "")? $recieveMessage->bot_reply : '-';
            $recieveMessageDatas[$key]['countKeyword'] = $recieveMessage->countKeyword;
            $recieveMessageDatas[$key]['created_at'] = $recieveMessage->created_at->format('Y-m-d H:i:s');
        }
        // dd($recieveMessages->take(10));
        return response()->json([
            'datas' => $recieveMessageDatas,
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
        $datas = collect();
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $keyword = $request->keyword;
        $keywordCondition = $request->keyword_condition;
        $reply = $request->reply;
        $replyCondition = $request->reply_condition;
        $conf = (string)$request->conf;
        $confCondition = $request->conf_condition;
        $count = (string)$request->count;
        $countCondition = $request->count_condition;
        $date = $request->date;
        $dateCondition = $request->date_condition;
        // $recieveMessages = RecieveMessage::whereBetween('created_at', array($startDate, $endDate))->select('*', \DB::raw('count(*) as countKeyword'), \DB::raw('max(created_at) as created_at'))
        //          ->groupBy('keyword');
        $recieveMessages = RecieveMessage::select('*', \DB::raw('count(*) as countKeyword'), \DB::raw('max(created_at) as created_at'))
                 ->groupBy('keyword','bot_reply');
        if($keyword != ""){
            if($keywordCondition == '='){
                $recieveMessages = $recieveMessages->where('keyword',$keyword);
            }else{
                $recieveMessages = $recieveMessages->where('keyword','like','%'.$keyword.'%');
            }

        }
        if($reply != ""){
            if($replyCondition == '='){
                $recieveMessages = $recieveMessages->where('bot_reply',$reply);
            }else{
                $recieveMessages = $recieveMessages->where('bot_reply','like','%'.$reply.'%');
            }

        }
        if($conf != ""){
            $condition = "";
            if($confCondition == '<'){
                $condition = "<";
            }
            if($confCondition == '>'){
                $condition = ">";
            }
            if($confCondition == '<='){
                $condition = "<=";
            }
            if($confCondition == '>'){
                $condition = ">=";
            }
            if($confCondition == '='){
                $condition = "=";
            }
            if($confCondition == '!='){
                $condition = "<>";
            }
            $recieveMessages = $recieveMessages->where('bot_conf',$condition,$conf);
        }
        if($date != ""){
            $condition = "";
            if($dateCondition == '<'){
                $condition = "<";
            }
            if($dateCondition == '>'){
                $condition = ">";
            }
            if($dateCondition == '<='){
                $condition = "<=";
            }
            if($dateCondition == '>'){
                $condition = ">=";
            }
            if($dateCondition == '='){
                $condition = "=";
            }
            if($dateCondition == '!='){
                $condition = "<>";
            }
            $recieveMessages = $recieveMessages->where('created_at',$condition,$date);
        }

        $recieveMessages = $recieveMessages->orderByDesc('created_at')->get();
        $datas = collect();
        if($count != ""){
            $condition = "";
            if($countCondition == '<'){
                $condition = "<";
            }
            if($countCondition == '>'){
                $condition = ">";
            }
            if($countCondition == '<='){
                $condition = "<=";
            }
            if($countCondition == '>'){
                $condition = ">=";
            }
            if($countCondition == '='){
                $condition = "=";
            }
            if($countCondition == '!='){
                $condition = "<>";
            }
            $recieveMessages = $recieveMessages->where('countKeyword',$condition,$count);
            foreach ($recieveMessages as $key => $recieveMessage) {
                $datas->push($recieveMessage);
            }
            $recieveMessages = $datas;
        }

        return response()->json([
            'datas' => $recieveMessages,
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
        //
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
        //
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

    public function exportDataCsv(Request $request)
    {
        $datas = [];
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $recieveMessages = RecieveMessage::whereBetween('created_at', array($startDate, $endDate))->select('*', \DB::raw('count(*) as countKeyword'), \DB::raw('max(created_at) as created_at'))
                 ->groupBy('keyword','mid')->orderByDesc('created_at')->get();
        foreach ($recieveMessages as $key => $recieveMessage) {
            $datas[$key]['No'] = $key+1;
            $datas[$key]['LINE_user_id'] = ($recieveMessage->lineUserProfile)? $recieveMessage->lineUserProfile->id : null;
            $datas[$key]['LINE_display_name'] = ($recieveMessage->lineUserProfile)? $recieveMessage->lineUserProfile->name : null;
            $datas[$key]['User_keyword'] = $recieveMessage->keyword;
            $datas[$key]['Bot_reply_keyword'] = $recieveMessage->bot_reply;
            $datas[$key]['Conf'] = $recieveMessage->bot_conf;
            $datas[$key]['Count'] = $recieveMessage->countKeyword;
            $datas[$key]['Date_time'] = "'".$recieveMessage->created_at->format('d/m/Y H:i:s');
        }
        $dateNow = Carbon::now()->format('dmY_Hi');

        Excel::create('report_auto_reply_'.$dateNow, function($excel) use ($datas) {
            $excel->sheet('sheet1', function($sheet) use ($datas)
            {
                $sheet->fromArray($datas);
            });
        })->download('csv');
    }
}
