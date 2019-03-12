<?php

namespace YellowProject\DownloadFile;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Subscriber;
use YellowProject\SubscriberLine;
use YellowProject\DownloadFile\DownloadFile;
use YellowProject\DownloadFile\DownloadFileMain;
use YellowProject\TrackingRecieveBc;
use YellowProject\LineUserProfile;
use YellowProject\RecieveMessage;
use Excel;
use URL;

class DownloadFileMainFunction extends Model
{
    public static function downloadFileSubscriberSingle($id)
    {
    	$datas = [];
        $dataExports = [];
        $activitySubscriberIds = $id;
        $subscriberActivities = SubscriberLine::where('subscriber_id',$activitySubscriberIds)->get()->groupBy('line_user_id');
        $count = 1;
        $dateNow = \Carbon\Carbon::now()->format('dmY_His');
        $subscriber = Subscriber::find($id);
        $name = $subscriber->name.'_'.$dateNow;
        $path = 'download_files/subscriber/'.\Carbon\Carbon::now()->format('d-m-Y');
        DownloadFile::checkFolderSubscriber($path);
        $downloadFile = DownloadFile::create([
          'file_name' => $name,
          'file_link_path' => URL::to('/')."/".$path."/".$name.".xlsx",
          'file_type' => 'subscriber',
          'status' => 'Pending',
          'deleted_at' => \Carbon\Carbon::now()->addDays(5)->format('Y-m-d H:i:s'),
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
        })->store('xlsx',public_path().'/'.$path);

        $downloadFile->update([
          'status' => 'Success'
        ]);
    }

    public static function downloadFileKeywordInquiry($requests)
    {
        $datas = [];
        $dateNow = \Carbon\Carbon::now()->format('dmY_His');
        $name = 'keyword_inquiry'.$requests['keyword_search']."_".$dateNow;
        $path = 'download_files/keyword_inquiry/'.\Carbon\Carbon::now()->format('d-m-Y');
        DownloadFile::checkFolderSubscriber($path);
        $downloadFile = DownloadFile::create([
          'file_name' => $name,
          'file_link_path' => URL::to('/')."/".$path."/".$name.".xlsx",
          'file_type' => 'keyword_inquiry',
          'status' => 'Pending',
          'deleted_at' => \Carbon\Carbon::now()->addDays(5)->format('Y-m-d H:i:s'),
        ]);

        $keywordSearch = $requests['keyword_search'];
        $isUnique = $requests['is_unique'];
        $recieveMessages = RecieveMessage::select('*', \DB::raw('count(*) as countKeyword'), \DB::raw('max(created_at) as created_at'));
        $recieveMessages = $recieveMessages->groupBy('keyword','mid')->orderByDesc('created_at');
        if($keywordSearch != -1){
            $recieveMessages->where('keyword',$keywordSearch);
        }
        
        $recieveMessages = $recieveMessages->get();
        if($isUnique){
            $totalSum = $recieveMessages->count();
        }else{
            $totalSum = $recieveMessages->sum('countKeyword');
        }

        foreach ($recieveMessages as $key => $recieveMessage) {
            $datas[$key]['UserId'] = $recieveMessage->lineUserProfile->mid;
            $datas[$key]['Display Name'] = $recieveMessage->lineUserProfile->name;
            $datas[$key]['Keyword'] = $recieveMessage->keyword;
            // $datas[$key]['Reply'] = "-";
            if($isUnique){
                $datas[$key]['Count'] = 1;
            }else{
                $datas[$key]['Count'] = $recieveMessage->countKeyword;
            }
            $datas[$key]['Date/Time'] = $recieveMessage->created_at->format('Y-m-d H:i:s');
        }

        Excel::create($name, function($excel) use ($datas) {
            $excel->sheet('sheet1', function($sheet) use ($datas)
            {
                $sheet->fromArray($datas);
            });
        })->store('xlsx',public_path().'/'.$path);

        $downloadFile->update([
          'status' => 'Success'
        ]);
    }
}
