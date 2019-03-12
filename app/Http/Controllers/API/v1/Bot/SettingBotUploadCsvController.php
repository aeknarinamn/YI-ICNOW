<?php

namespace YellowProject\Http\Controllers\API\v1\Bot;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\Bot\TrainBotCsv;
use YellowProject\Bot\TrainBotCsvLog;
use YellowProject\Bot\TrainBotCsvLogItem;
use YellowProject\ConnectBotNoi;
use Carbon\Carbon;
use Excel;

class SettingBotUploadCsvController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = TrainBotCsv::all();

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $datas = [];
        $trainBotCsv = TrainBotCsv::find($id);
        $trainBotCsvLogs = $trainBotCsv->trainBotCsvLogs;
        $datas['name'] = $trainBotCsv->name;
        $datas['conf'] = $trainBotCsv->conf;
        $datas['items'] = [];
        if($trainBotCsvLogs->count() > 0){
            foreach ($trainBotCsvLogs as $key => $trainBotCsvLog) {
                $trainBotCsvLogItems = $trainBotCsvLog->trainBotCsvLogItems;
                if($trainBotCsvLogItems){
                    foreach ($trainBotCsvLogItems as $index => $trainBotCsvLogItem) {
                        $datas['items'][$index]['id'] = $trainBotCsvLogItem->id;
                        $datas['items'][$index]['question'] = $trainBotCsvLogItem->question;
                        $datas['items'][$index]['answer'] = $trainBotCsvLogItem->answer;
                        $datas['items'][$index]['status'] = $trainBotCsvLogItem->status;
                    }
                }
            }
        }

        return response()->json([
            'datas' => $datas,
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
        $trainBotCsv = TrainBotCsv::find($id);
        $trainBotCsvLogs = $trainBotCsv->trainBotCsvLogs;
        if($trainBotCsvLogs){
            foreach ($trainBotCsvLogs as $key => $trainBotCsvLog) {
                $trainBotCsvLogItems = $trainBotCsvLog->trainBotCsvLogItems;
                if($trainBotCsvLogItems){
                    foreach ($trainBotCsvLogItems as $key => $trainBotCsvLogItem) {
                        $ask = $trainBotCsvLogItem->question;
                        $ans = $trainBotCsvLogItem->answer;
                        ConnectBotNoi::removeBotTrain($ask,$ans);
                        $trainBotCsvLogItem->delete();
                    }
                }
                $trainBotCsvLog->delete();
            }
        }
        $trainBotCsv->delete();

        return response()->json([
            'msg_return' => 'ลบข้อมูลสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function uploadCsvTrainBot(Request $request)
    {
        $trainBotId = $request->id;
        if($trainBotId == 0){
            $trainBotCsv = TrainBotCsv::create($request->all());
        }else{
            $trainBotCsv = TrainBotCsv::find($trainBotId);
            $trainBotCsv->update($request->all());
        }

        if(isset($request->items)){
            $trainBotCsvLog = TrainBotCsvLog::create([
                'train_bot_csv_id' => $trainBotCsv->id,
            ]);
            $items = $request->items;
            foreach ($items as $key => $item) {
                $connectbotTrain = ConnectBotNoi::connectTrainBot($item);
                $flag = "Fail";
                if($connectbotTrain == 1){
                    $flag = "Success";
                }

                TrainBotCsvLogItem::create([
                    'train_bot_csv_log_id' => $trainBotCsvLog->id,
                    'question' => $item['question'],
                    'answer' => $item['answer'],
                    'status' => $flag,
                ]);
            }
        }

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function uploadResult()
    {
        $datas = [];
        $trainBotCsvs = TrainBotCsv::all();
        $count = 0;
        foreach ($trainBotCsvs as $trainBotCsvIndex => $trainBotCsv) {
            $trainBotCsvLogs = $trainBotCsv->trainBotCsvLogs;
            if($trainBotCsvLogs){
                foreach ($trainBotCsvLogs as $trainBotCsvLogIndex => $trainBotCsvLog) {
                    $trainingUpload = 0;
                    $trainingUploadSuccess = 0;
                    $trainingUploadFail = 0;
                    $trainBotCsvLogItems = $trainBotCsvLog->trainBotCsvLogItems;
                    if($trainBotCsvLogItems){
                        $trainingUpload = $trainBotCsvLogItems->count();
                        $trainingUploadSuccess = $trainBotCsvLogItems->where('status','Success')->count();
                        $trainingUploadFail = $trainBotCsvLogItems->where('status','Fail')->count();
                    }
                    $datas[$trainBotCsvIndex]['train_bot_name'] = $trainBotCsv->name;
                    $datas[$trainBotCsvIndex]['train_upload'] = $trainingUpload;
                    $datas[$trainBotCsvIndex]['train_upload_success'] = $trainingUploadSuccess;
                    $datas[$trainBotCsvIndex]['train_upload_fail'] = $trainingUploadFail;
                    $datas[$trainBotCsvIndex]['date'] = $trainBotCsvLog->created_at->format('d/m/Y H:i:s');
                }
            }
        }

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function removeSingle($id)
    {
        $trainBotCsvLogItem = TrainBotCsvLogItem::find($id);
        $ask = $trainBotCsvLogItem->question;
        $ans = $trainBotCsvLogItem->answer;
        ConnectBotNoi::removeBotTrain($ask,$ans);
        $trainBotCsvLogItem->delete();

        return response()->json([
            'msg_return' => 'ลบสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function exportTrainbotCSV($id)
    {
        $dataExports = [];
        $trainBotCsvs = TrainBotCsv::all();
        $count = 1;
        foreach ($trainBotCsvs as $trainBotCsvIndex => $trainBotCsv) {
            $trainBotCsvLogs = $trainBotCsv->trainBotCsvLogs;
            if($trainBotCsvLogs){
                foreach ($trainBotCsvLogs as $trainBotCsvLogIndex => $trainBotCsvLog) {
                    $trainBotCsvLogItems = $trainBotCsvLog->trainBotCsvLogItems;
                    if($trainBotCsvLogItems){
                        foreach ($trainBotCsvLogItems as $key => $trainBotCsvLogItem) {
                            $dataExports[$count]['No.'] = $count;
                            $dataExports[$count]['Ask'] = $trainBotCsvLogItem->question;
                            $dataExports[$count]['Ans'] = $trainBotCsvLogItem->answer;
                            $dataExports[$count]['Status'] = $trainBotCsvLogItem->status;
                            $dataExports[$count]['Created At'] = $trainBotCsvLogItem->created_at->format('d/m/Y H:i:s');
                            $count++;
                        }
                    }
                }
            }
        }

        $dateNow = \Carbon\Carbon::now()->format('dmY_His');

        Excel::create('bot_train_'.$dateNow, function($excel) use ($dataExports) {
            $excel->sheet('sheet_1', function($sheet) use ($dataExports)
            {
                $sheet->fromArray($dataExports);
            });
        })->download('xlsx');
    }

    public function retryBot($id)
    {
        $items = [];
        $flag = "Fail";
        $trainBotCsvLogItem = TrainBotCsvLogItem::find($id);
        if($trainBotCsvLogItem){
            $ask = $trainBotCsvLogItem->question;
            $ans = $trainBotCsvLogItem->answer;
            $items['question'] = $ask;
            $items['answer'] = $ans;
            $connectbotTrain = ConnectBotNoi::connectTrainBot($items);
            if($connectbotTrain == 1){
                $flag = "Success";
            }

            $trainBotCsvLogItem->update([
                'status' => $flag,
            ]);

            return response()->json([
                'msg_return' => 'บันทึกสำเร็จ',
                'code_return' => 1,
            ]);
        }else{
            return response()->json([
                'msg_return' => 'ไม่พบข้อมูล',
                'code_return' => 2,
            ]);
        }
        
    }
}
