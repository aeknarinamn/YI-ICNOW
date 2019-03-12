<?php

namespace YellowProject\Http\Controllers\API\v1\Bot;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\Bot\TrainBotCsv;
use YellowProject\Bot\TrainBotAnswerCsv;
use YellowProject\ConnectBotNoi;

class SettingBotUploadCsvController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = collect();
        $trainBotCsvs = TrainBotCsv::all();
        foreach ($trainBotCsvs as $key => $trainBotCsv) {
            $trainBotAnswerCsvs = $trainBotCsv->botTrainAnswerCsvs;
            $data = [];
            $data['keyword'] = $trainBotCsv->question;
            $data['count'] = $trainBotAnswerCsvs->count();
            $data['date'] = $trainBotAnswerCsvs->last()->created_at->format('d-m-Y H:i:s');
            $datas->push($data);
        }

        return response()->json([
            'datas' => $datas,
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
        // if($request->id != 0){
        //     $trainBot = TrainBot::find($request->id);
        // }else{
        //     $trainBot = TrainBot::create($request->all());
        // }
        // foreach ($request->items as $key => $items) {
        //     $items['fact_bot_train_id'] = $trainBot->id;
        //     TrainBotAnswer::create($items);
        //     $datas['question'] = $request->question;
        //     $datas['answer'] = $items['answer'];
        //     ConnectBotNoi::connectTrainBot($datas);
        // }

        // return response()->json([
        //     'msg_return' => 'บันทึกสำเร็จ',
        //     'code_return' => 1,
        // ]);
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

    public function uploadCsvTrainBot(Request $request)
    {
        $question = $request->question;
        $answer = $request->answer;
        $trainBotCsv = TrainBotCsv::where('question',$question)->first();
        if($trainBotCsv){
            $trainBotAnswerCsv = TrainBotAnswerCsv::where('fact_bot_train_csv_id',$trainBotCsv->id)->where('answer',$answer)->first();
            if(!$trainBotAnswerCsv){
                TrainBotAnswerCsv::create([
                    'fact_bot_train_csv_id' => $trainBotCsv->id,
                    'answer' => $answer,
                ]);

                $datas = [];
                $datas['question'] = $question;
                $datas['answer'] = $answer;
                ConnectBotNoi::connectTrainBot($datas);
            }
        }else{
            $trainBotCsv = TrainBotCsv::create([
                'question' => $question,
            ]);

            TrainBotAnswerCsv::create([
                'fact_bot_train_csv_id' => $trainBotCsv->id,
                'answer' => $answer,
            ]);

            $datas = [];
            $datas['question'] = $question;
            $datas['answer'] = $answer;
            ConnectBotNoi::connectTrainBot($datas);
        }

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function uploadCsvRemoveBot(Request $request)
    {
        $question = $request->question;
        $trainBotCsv = TrainBotCsv::where('question',$question)->first();
        $trainBotAnswerCsvs = $trainBotCsv->botTrainAnswerCsvs;
        foreach ($trainBotAnswerCsvs as $key => $trainBotAnswerCsv) {
            $trainBotAnswerCsv->delete();
        }
        $trainBotCsv->delete();
        ConnectBotNoi::removeBotTrain($question);

        return response()->json([
            'msg_return' => 'ลบข้อมูลสำเร็จ',
            'code_return' => 1,
        ]);
    }
}
