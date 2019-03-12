<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\Subscriber;
use YellowProject\SubscriberLine;
use YellowProject\LineUserProfile;
use YellowProject\Field;
use YellowProject\DownloadFile\DownloadFile;
use Excel;
use URL;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscribers = Subscriber::where('is_master',0)->get();
        foreach ($subscribers as $key => $subscriber) {
            $subscriberFolder = $subscriber->folder;
        }
        return response()->json([
            'datas' => $subscribers,
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
        $subscriberSearch = Subscriber::where('name',$request->name)->first();
        if($subscriberSearch){
          return response()->json([
              'msg_return' => 'ชื่อซ้ำกัน',
              'code_return' => 2,
          ]);
        }
        $subscriber = Subscriber::create($request->all());
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
        $subscriber = Subscriber::find($id);
        $subscriberFolder = $subscriber->folder;
        return response()->json([
            'datas' => $subscriber,
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
        $subscriber = Subscriber::find($id);
        $subscriberSearch = Subscriber::where('name',$request->name)->first();
        if($subscriberSearch && $request->name != $subscriber->name){
          return response()->json([
              'msg_return' => 'ชื่อซ้ำกัน',
              'code_return' => 2,
          ]);
        }
        $subscriber->update($request->all());
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
        $subscriber = Subscriber::find($id);
        if($subscriber->fields->count() > 0){
          return response()->json([
              'msg_return' => 'Subscriber used by fields.',
              'code_return' => 2,
          ]);
        }else{
          $subscriber->delete();
          return response()->json([
              'msg_return' => 'บันทึกสำเร็จ',
              'code_return' => 1,
          ]);
        }
    }

    public function getField($id)
    {
        $subscriber = Subscriber::find($id);
        $fields = $subscriber->fields;
        foreach ($fields as $key => $field) {
            $field->fieldItems;
        }
        // dd($subscriber->fields);
        return response()->json([
            'datas' => $subscriber,
        ]);
    }

    public function downloadSubscriber(Request $request)
    {
        $datas = [];
        $dataExports = [];
        $registerSubscriberId = $request->register_subscriber_id;
        $activitySubscriberIds = explode('|', $request->activity_subscriber_id);
        $subscriberRegister = SubscriberLine::where('subscriber_id',$registerSubscriberId)->get()->groupBy('line_user_id');
        $subscriberActivities = SubscriberLine::whereIn('subscriber_id',$activitySubscriberIds)->get()->groupBy('line_user_id');
        $count = 1;
        $dateNow = \Carbon\Carbon::now()->format('dmY_His');
        $name = 'subscriber_data_'.$dateNow;
        $path = 'download_files/subscriber/'.\Carbon\Carbon::now()->format('d-m-Y');
        DownloadFile::checkFolderSubscriber($path);
        $downloadFile = DownloadFile::create([
          'file_name' => $name,
          'file_link_path' => URL::to('/')."/".$path."/".$name.".xlsx",
          'file_type' => 'subscriber',
          'status' => 'Pending'
        ]);

        foreach ($subscriberActivities as $idKey => $subscribers) {
            $datas[$idKey]['No.'] = $count;
            $datas[$idKey]['userID'] = $idKey;
            foreach ($subscribers as $subscriber) {
                foreach ($subscriber->subscriberItems as $subscriberItem) {
                    // $datas[$idKey][$subscriberItem->field->name] = "'".$subscriberItem->value;
                    if($subscriberItem->value == ""){
                      $datas[$idKey][$subscriberItem->field->name] = 'N/A';
                    }else{
                      $datas[$idKey][$subscriberItem->field->name] = $subscriberItem->value;
                    }
                }
                $datas[$idKey]['created_at_'.$subscriber->subscriber->name] = $subscriber->created_at->format('Y-m-d H:i:s');
            }
            $count++;
        }

        foreach ($subscriberRegister as $idKey => $subscribers) {
            if(array_key_exists($idKey, $datas)){
                foreach ($subscribers as $subscriber) {
                    $datas[$idKey]['subscriber_line_id'] = $subscriber->line_user_id;
                    foreach ($subscriber->subscriberItems as $subscriberItem) {
                        // $datas[$idKey][$subscriberItem->field->name] = "'".$subscriberItem->value;
                        if($subscriberItem->value == ""){
                          $datas[$idKey][$subscriberItem->field->name] = 'N/A';
                        }else{
                          $datas[$idKey][$subscriberItem->field->name] = $subscriberItem->value;
                        }
                    }
                    $datas[$idKey]['created_at'] = $subscriber->created_at->format('d/m/Y H:i:s');
                }
            }
        }

        if(count($datas) > 0){
            array_multisort(array_map('count', $datas), SORT_DESC, $datas);
            $allKeys = array_keys($datas[0]);

            foreach ($datas as $index => $data) {
                foreach ($allKeys as $key) {
                    if(!array_key_exists($key, $data)){
                        $dataExports[$index][$key] = "N/A";
                    }else{
                        $dataExports[$index][$key] = $data[$key];
                    }
                }
                $dataExports[$index]['No.'] = $index+1;
            }
        }

        // dd($dataExports);
        // dd($datas);
        

        Excel::create($name, function($excel) use ($dataExports) {
            $excel->sheet('sheet1', function($sheet) use ($dataExports)
            {
                $sheet->fromArray($dataExports);
            });
        })->store('xlsx',$path);

        $downloadFile->update([
          'status' => 'Success'
        ]);
    }

    public function downloadSubscriberSingle($id)
    {
        $datas = [];
        $dataExports = [];
        $activitySubscriberIds = $id;
        $subscriberActivities = SubscriberLine::where('subscriber_id',$activitySubscriberIds)->get()->groupBy('line_user_id');
        $count = 1;
        $dateNow = \Carbon\Carbon::now()->format('dmY_His');
        $name = 'subscriber_data_'.$dateNow;
        $path = 'download_files/subscriber/'.\Carbon\Carbon::now()->format('d-m-Y');
        DownloadFile::checkFolderSubscriber($path);
        $downloadFile = DownloadFile::create([
          'file_name' => $name,
          'file_link_path' => URL::to('/')."/".$path."/".$name.".xlsx",
          'file_type' => 'subscriber',
          'status' => 'Pending'
        ]);

        foreach ($subscriberActivities as $idKey => $subscribers) {
            $datas[$idKey]['No.'] = $count;
            $datas[$idKey]['userID'] = $idKey;
            foreach ($subscribers as $subscriber) {
                foreach ($subscriber->subscriberItems as $subscriberItem) {
                    // $datas[$idKey][$subscriberItem->field->name] = "'".$subscriberItem->value;
                    if($subscriberItem->value == ""){
                      $datas[$idKey][$subscriberItem->field->name] = 'N/A';
                    }else{
                      $datas[$idKey][$subscriberItem->field->name] = $subscriberItem->value;
                    }
                }
                $datas[$idKey]['created_at_'.$subscriber->subscriber->name] = $subscriber->created_at->format('Y-m-d H:i:s');
            }
            $count++;
        }

        if(count($datas) > 0){
            array_multisort(array_map('count', $datas), SORT_DESC, $datas);
            $allKeys = array_keys($datas[0]);

            foreach ($datas as $index => $data) {
                foreach ($allKeys as $key) {
                    if(!array_key_exists($key, $data)){
                        $dataExports[$index][$key] = "N/A";
                    }else{
                        $dataExports[$index][$key] = $data[$key];
                    }
                }
                $dataExports[$index]['No.'] = $index+1;
            }
        }

        Excel::create($name, function($excel) use ($dataExports) {
            $excel->sheet('sheet1', function($sheet) use ($dataExports)
            {
                $sheet->fromArray($dataExports);
            });
        })->store('xlsx',$path);

        $downloadFile->update([
          'status' => 'Success'
        ]);
    }

    public function getData($id)
    {
        $datas = [];
        $subscriberLines = SubscriberLine::where('subscriber_id',$id)->get();
        $fields = Field::where('subscriber_id',$id)->get();

        foreach ($subscriberLines as $keySubscriberLine => $subscriberLine) {
            $datas[$keySubscriberLine]['avatar'] = $subscriberLine->lineUserProfile->avatar;
            $datas[$keySubscriberLine]['display_name'] = $subscriberLine->lineUserProfile->name;
            if($subscriberLine){
                $subscriberItems = $subscriberLine->subscriberItems;
                foreach ($fields as $keyField => $field) {
                    $datas[$keySubscriberLine][$field->name] = ($subscriberItems->where('field_id',$field->id)->first())? $subscriberItems->where('field_id',$field->id)->first()->value : null ;
                }
            }
        }

        // dd($datas);
        // dd($subscriber->fields);
        return response()->json([
            'datas' => $datas,
        ]);
    }
}
