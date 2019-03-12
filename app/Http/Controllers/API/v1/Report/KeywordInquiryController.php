<?php

namespace YellowProject\Http\Controllers\API\v1\Report;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\RecieveMessage;
use YellowProject\DownloadFile\DownloadFile;
use YellowProject\DownloadFile\DownloadFileMain;
use Excel;

class KeywordInquiryController extends Controller
{
    public function keywordInquiryReport(Request $request)
    {
    	$datas = [];
    	$keywordSearch = $request->keyword_search;
    	$isUnique = $request->is_unique;
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
            $datas[$key]['line_display_name'] = $recieveMessage->lineUserProfile->name;
            $datas[$key]['keyword'] = $recieveMessage->keyword;
            // $datas[$key]['reply'] = "-";
            if($isUnique){
	    		$datas[$key]['countKeyword'] = 1;
	    	}else{
	    		$datas[$key]['countKeyword'] = $recieveMessage->countKeyword;
	    	}
            $datas[$key]['created_at'] = $recieveMessage->created_at->format('Y-m-d H:i:s');
        }

    	return response()->json([
            'datas' => $datas,
            'total_count' => $totalSum
        ]);
    }

    public function keywordInquiryReportExport(Request $request)
    {
    	$serialized = serialize($request->all());
        
        DownloadFileMain::create([
          'main_id' => 0,
          'type' => 'keyword_inquiry',
          'requests' => $serialized,
          'is_active' => 1
        ]);
        
        return response()->json([
          'return_code' => 1,
          'msg' => 'Success"',
        ]);
    }
}
