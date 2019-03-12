<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use YellowProject\ChatMain;
use YellowProject\ChatMessage;

class GenerateTxt extends Model
{
    public static function genTxt($file,$content)
    {
    	$path = $_SERVER['DOCUMENT_ROOT'].'/log/'.'log'.'_'.Carbon::now()->format('d-m-Y').'.txt';
    	// $content = iconv("tis-620","UTF-8",$content);
    	$contents = Carbon::now()->format('d-m-Y h:i:s')." ".$content;
    	file_put_contents($path, $contents . PHP_EOL, FILE_APPEND);
    }

    public static function genCountMainChat()
    {
    	try {
    		$countMainChat = ChatMain::all()->count();
	    	$countChatMessage = ChatMessage::all()->count();
	    	$path = $_SERVER['DOCUMENT_ROOT'].'/chat/'.'main-chat.txt';
	    	$contents = $countMainChat."/".$countChatMessage;
	    	file_put_contents($path, $contents);
    	}
    	catch(Exception $e) {
            Log::debug('Error '.$e);
        }
    }
}
