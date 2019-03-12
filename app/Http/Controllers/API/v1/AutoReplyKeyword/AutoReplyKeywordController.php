<?php

namespace YellowProject\Http\Controllers\API\v1\AutoReplyKeyword;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\KeywordFolder;
use YellowProject\AutoReplyKeyword;
use YellowProject\AutoReplyKeywordMessage;
use YellowProject\Keyword;
use YellowProject\AutoReplyKeywordItem;
use Excel;
use Carbon\Carbon;

class AutoReplyKeywordController extends Controller
{
    public function uploadAutoReplyFile(Request $request)
    {
    	$keywordFolder = KeywordFolder::create([
    		"folder_name" => "Location",
    		"description" => "Location"
    	]);
    	$file = $request->auto_reply_file;
    	// dd($request->all());
    	$excels = Excel::load($file, function($reader) {
		})->get();
		$datas = $excels->all();
		$sentDate = Carbon::now()->format('Y-m-d H:i:s');
		$lastSentDate = Carbon::now()->addYear(1)->format('Y-m-d H:i:s');
		foreach ($datas as $key => $data) {
			$autoReplyData = $data->all();
			$autoreplyKeyword = AutoReplyKeyword::create([
				"title" => $autoReplyData['title'],
				"active" => 1,
				"sent_date" => $sentDate,
				"last_sent_date" => $lastSentDate,
				"folder_id" => $keywordFolder->id,
				"report_tag_id" => null,
			]);

			$autoReplyKeywordMessage = AutoReplyKeywordMessage::create([
				"message" => $autoReplyData['text_1'],
				"display" => $autoReplyData['text_1'],
			]);

			$keyword = Keyword::create([
				"keyword" => $autoReplyData['keyword'],
				"active" => 1,
				"dim_auto_reply_keywords_id" => $autoreplyKeyword->id,
			]);

			AutoReplyKeywordItem::create([
				"dim_auto_reply_keyword_id" => $autoreplyKeyword->id,
				"message_type_id" => "text",
				"seq_no" => 1,
				"auto_reply_message_id" => $autoReplyKeywordMessage->id,
				"auto_reply_sticker_id" => null,
				"auto_reply_richmessage_id" => null,
			]);
			// dd($autoReplyData);
		}

		echo "Success";
    }
}
