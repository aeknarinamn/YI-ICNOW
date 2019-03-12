<?php
namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Greeting\Greeting;
use YellowProject\Greeting\GreetingItem;
use YellowProject\Greeting\GreetingItemMessage;
use YellowProject\Greeting\GreetingItemSticker;
use YellowProject\LineWebHooks;
use Response;

class GreetingMessage extends Model
{
    public static function followBussiness($body)
    {
    	// return redirect()->action('Auth\AuthController@redirectToProvider');
    	$mid = $body['events'][0]['source']['userId'];
    	GreetingMessage::sendGreeting($mid);
    	// dd($mid);
    	// Log::debug();
    	// dd('kk');
    	// Log::debug('Mid : '.$mid);
    }

    public static function sendRichmessage($mid,$path)
    {
    	$lineSettingBusiness = \YellowProject\LineSettingBusiness::where('active',true)->first();
    	$datas = collect();
    	$messages = array();
        $messages = \YellowProject\LineWebHooks::setImagemap(6);
        $datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);
        $message = collect($messages);
        $data = collect([
            "to" => $mid,
            // "replyToken" => $datas['replyToken'], // for test
            "messages"   => $message
        ]);
        $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/push');
        $datas->put('data', $data->toJson());
        \YellowProject\LineWebHooks::sent($datas);
    }

    public static function getUserProfile($mid)
    {
    	$lineSettingBusiness = \YellowProject\LineSettingBusiness::where('active', 1)->first();
    	$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.line.me/v2/bot/profile/".$mid,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "authorization: Bearer ".$lineSettingBusiness->channel_access_token,
		    "cache-control: no-cache",
		    "postman-token: e53e2035-dd4a-9648-e11c-2f2909e34c8c"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}

		return json_decode($response,true);
    }

    public static function sendMessageGreeting($mid,$lineUserProfile,$replyToken)
    {
    	$lineSettingBusiness = \YellowProject\LineSettingBusiness::where('active',true)->first();
		$datas = collect();
    	$datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);
        $messages = array();
        $greeting  = Greeting::where('active',1)->first();
        if(!is_null($greeting)) {
            $items  = $greeting->greetingItems;
            $messages = array();
            if (sizeof($items) > 0) {
                foreach ($items as $item) {
                    if ($item->messageType->type == 'text') {
                        $messages[]  = [
                            "type" =>"text",
                            "text" => GreetingItemMessage::encodeMessageEmo($item->message->message,$lineUserProfile)
                        ];                                       
                    }  elseif ($item->messageType->type == 'sticker') {
                        $messages[]  = [
                            "type" =>"sticker",
                            "packageId" => (string) $item->sticker->packageId,
                            "stickerId" => (string) $item->sticker->stickerId,
                        ];                                       
                    } elseif ($item->messageType->type == 'imagemap') {
                        // $messages = array();
                        $messages[] = LineWebHooks::setImagemap($item->greeting_richmessage_id);
                        // Log::debug($messages);
                    }
                }
            }                          
		
	        $message = collect($messages);
	            
	        $data = collect([
	            // "to" => $mid,
	            "replyToken" => $replyToken,
	            "messages"   => $message
	        ]);

	        $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/reply');
	        $datas->put('data', $data->toJson());
	        LineWebHooks::sent($datas);
        }

        return response()->json(['status' => 'ok'], 200);
	}

}
