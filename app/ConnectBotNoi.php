<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Log;
use YellowProject\BotTrain;
use YellowProject\Bot\SettingBot;

class ConnectBotNoi extends Model
{
    public static function connectTrainBot($datas)
    {
    	$settingBot = SettingBot::first();
    	Log::debug('Bot Train');
    	Log::debug($datas);
    	$question = $datas['question'];
    	$answer = $datas['answer'];
    	// $url = "https://fwd-api.herokuapp.com/train?ask=".urlencode($question)."&ans=".urlencode($answer)."&tnid=1&sf=1";
    	$url = $settingBot->bot_train_url."/train?ask=".urlencode($question)."&ans=".urlencode($answer)."&tnid=1&sf=1";
    	Log::debug($url);
    	$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache",
		    "postman-token: 012cce55-c1e0-b4f4-b7ff-4911dc5721cf"
		  ),
		));

		$response = curl_exec($curl);
		Log::debug('Bot Train Response =>'. $response);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  Log::debug("cURL Error #:" . $err);
		} else {
		  Log::debug($response);
		}

		return json_decode($response);
    }

    public static function connectReply($wording)
    {
    	$settingBot = SettingBot::first();
    	Log::debug('Bot Reply');
    	Log::debug($wording);

    	$curl = curl_init();

		curl_setopt_array($curl, array(
		  // CURLOPT_URL => "http://fwd-api.herokuapp.com/reply?ask=".urlencode($wording)."&uid=1&sf=1",
		  CURLOPT_URL => $settingBot->bot_reply_url."/reply?ask=".urlencode($wording)."&uid=1&sf=1",
		  //CURLOPT_URL => "https://landandhouse.herokuapp.com/reply?ask=".urlencode($wording)."&uid=1&sf=1",
		  // CURLOPT_URL => "http://scg-api.herokuapp.com/reply?ask=".urlencode($wording)."&uid=1&sf=1",

		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache",
		    "postman-token: 6d713ddc-cd3f-8a4f-bed5-68ef996375f6"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  Log::debug("cURL Error #:" . $err);
		} else {
		  Log::debug($response);
		}
    	
  		//   	$curl = curl_init();

		// curl_setopt_array($curl, array(
		//   CURLOPT_URL => "http://lh-api.herokuapp.com/reply?ask=".$wording."&uid=1&sf=1",
		//   CURLOPT_RETURNTRANSFER => true,
		//   CURLOPT_ENCODING => "",
		//   CURLOPT_MAXREDIRS => 10,
		//   CURLOPT_TIMEOUT => 30,
		//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		//   CURLOPT_CUSTOMREQUEST => "GET",
		//   CURLOPT_HTTPHEADER => array(
		//     "cache-control: no-cache",
		//     "postman-token: 15acfc34-1eb8-e73f-6853-3a57245e2ae8"
		//   ),
		// ));

		// $response = curl_exec($curl);
		// $err = curl_error($curl);

		// curl_close($curl);

		// if ($err) {
		//   echo "cURL Error #:" . $err;
		// } else {
		//   echo $response;
		// }

    	Log::debug($response);

		return json_decode($response);
    }

    public static function removeBotTrain($ask,$ans)
    {
    	$settingBot = SettingBot::first();
    	Log::debug('Bot remove');
    	Log::debug("ask => ".$ask."ans => ".$ans);

    	$curl = curl_init();

		curl_setopt_array($curl, array(
		  // CURLOPT_URL => "http://fwd-api.herokuapp.com/reply?ask=".urlencode($wording)."&uid=1&sf=1",
		  CURLOPT_URL => $settingBot->bot_remove_url."/remove?ask=".urlencode($ask)."&ans=".urlencode($ans)."&uid=1&sf=1",
		  //CURLOPT_URL => "https://landandhouse.herokuapp.com/reply?ask=".urlencode($wording)."&uid=1&sf=1",
		  // CURLOPT_URL => "http://scg-api.herokuapp.com/reply?ask=".urlencode($wording)."&uid=1&sf=1",

		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache",
		    "postman-token: 6d713ddc-cd3f-8a4f-bed5-68ef996375f6"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  Log::debug("cURL Error #:" . $err);
		} else {
		  Log::debug($response);
		}

    	Log::debug($response);

		return json_decode($response);
    }

    public static function restartBot()
    {
    	$settingBot = SettingBot::first();
    	Log::debug('Bot restart');

    	$curl = curl_init();

		curl_setopt_array($curl, array(
		  // CURLOPT_URL => "http://fwd-api.herokuapp.com/reply?ask=".urlencode($wording)."&uid=1&sf=1",
		  CURLOPT_URL => $settingBot->bot_restart_url."/restart",
		  //CURLOPT_URL => "https://landandhouse.herokuapp.com/reply?ask=".urlencode($wording)."&uid=1&sf=1",
		  // CURLOPT_URL => "http://scg-api.herokuapp.com/reply?ask=".urlencode($wording)."&uid=1&sf=1",

		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache",
		    "postman-token: 6d713ddc-cd3f-8a4f-bed5-68ef996375f6"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  Log::debug("cURL Error #:" . $err);
		} else {
		  Log::debug($response);
		}

    	Log::debug($response);

		return json_decode($response);
    }

    public static function setDataQuestionAndAwnser($string)
    {
    	// $string = "q:กินอะไรดี a:กินมะม่วงสิ";
    	// dd($string);
	    $isTrainBot = false;
	    $datas = [];
	    if (strpos($string, '^&') !== false) {
	        $isTrainBot = true;
	    }
	    if($isTrainBot == true){
	      $split = explode('^&', $string);
	      $datas['result'] = true;
	      $datas['question'] = $split[0];
	      $datas['answer'] = $split[1];
	    }else{
	      $datas['result'] = false;
	    }
	    // dd($datas);

	    return $datas;
    }

    public static function storeDataBotTrain($datas,$mid)
    {
    	BotTrain::create([
    		'question' => $datas['question'],
    		'answer'   => $datas['answer'],
    		'mid'      => $mid,
    	]);

    	return true;
    }
}
