<?php

namespace YellowProject\Http\Controllers\API\v1\Bot;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\Bot\TrainBot;
use YellowProject\Bot\TrainBotAnswer;
use YellowProject\ConnectBotNoi;

class BottrainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = [];
        $trainBots = TrainBot::all();
        foreach ($trainBots as $key => $trainBot) {
            $trainBot->botTrainAnswers;
            $datas[$key] = $trainBot;
        }

        return response()->json([
            'datas' => $trainBots,
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
        $trainBot = TrainBot::where('question',$request->question)->first();
        if(!$trainBot){
             $trainBot = TrainBot::create($request->all());
        }
        // if($request->id != 0){
        //     $trainBot = TrainBot::find($request->id);
        // }else{
        //     $trainBot = TrainBot::create($request->all());
        // }
        foreach ($request->items as $key => $items) {
            $items['fact_bot_train_id'] = $trainBot->id;
            TrainBotAnswer::create($items);
            $datas['question'] = $request->question;
            $datas['answer'] = $items['answer'];
            ConnectBotNoi::connectTrainBot($datas);
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
        // $trainBot = TrainBot::find($id);
        // $trainBot->update($request->all());
        // $trainBotAnswerIds = $trainBot->botTrainAnswers->pluck('id')->toArray();
        // foreach ($request->items as $key => $items) {
        //     $id = $items['id'];
        //     if($id == 0){
        //         $items['fact_bot_train_id'] = $trainBot->id;
        //         TrainBotAnswer::create($items);
        //         $datas['question'] = $request->question;
        //         $datas['answer'] = $request->answer;
        //         ConnectBotNoi::connectTrainBot($datas);
        //     }else{
        //        $trainBotAnswer = TrainBotAnswer::find($id);
        //        $trainBotAnswer->update($items);
        //        if(($key = array_search($items['id'], $trainBotAnswerIds)) !== false) {
        //             unset($trainBotAnswerIds[$key]);
        //         }
        //     }
        // }

        // if(isset($trainBotAnswerIds) && count($trainBotAnswerIds) > 0 ){
        //     TrainBotAnswer::whereIn('id',$trainBotAnswerIds)->delete();
        // }

        // return response()->json([
        //     'msg_return' => 'บันทึกสำเร็จ',
        //     'code_return' => 1,
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trainBot = TrainBot::find($id);
        if($trainBot->botTrainAnswers){
            foreach ($trainBot->botTrainAnswers as $key => $item) {
                $ask = $trainBot->question;
                $ans = $item->answer;
                ConnectBotNoi::removeBotTrain($ask,$ans);
                $item->delete();
            }
        }
        $trainBot->delete();

        return response()->json([
            'msg_return' => 'ลบสำเร็จ',
            'code_return' => 1,
        ]);
    }
}
