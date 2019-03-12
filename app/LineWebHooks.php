<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Log;
use Carbon\Carbon;
use YellowProject\RecieveMessage;
use YellowProject\LineUserProfile;
use YellowProject\RichMessageMain;
use YellowProject\LineSettingBusiness;
use YellowProject\LocationKeyword;
use YellowProject\ConnectBotNoi;
use YellowProject\BotTrain;
use YellowProject\LocationItem;
use YellowProject\CoroselConfirmation;
use YellowProject\CarouselItemKeyword;
use YellowProject\CarouselConfirmationItem;
use YellowProject\CarouselConfirmation;
use YellowProject\PushOrReply;
use YellowProject\ShareLocation\ShareLocation;
use YellowProject\ShareLocation\ShareLocationConfirmation;
use YellowProject\ShareLocation\ShareLocationConfirmationItem;
use YellowProject\ShareLocation\ShareLocationItem;
use YellowProject\ShareLocation\UserShareLocation;
use YellowProject\ConfirmationSetting\ConfirmationSettingShareLocation;
use YellowProject\ConfirmationSetting\ConfirmationSettingCarousel;
use YellowProject\GeneralFunction\LineNotification;
use YellowProject\Bot\SettingBot;
use YellowProject\RecieveMessage\RecieveMessageGroup;
use YellowProject\RecieveMessage\RecieveMessageRoom;
use YellowProject\BotJoinGroupAndRoom\AutoreplyKeyword\BotJoinKeyword;
use YellowProject\TemplateMessage\CoreFunction as TemplateMessageCoreFunction;
use YellowProject\RichmessageV2\Richmessage;

class LineWebHooks extends Model
{
    
	public static function checkSignature($header, $body)

	{
 
        $lineSettingBusiness = LineSettingBusiness::where('active',true)->first();
        if (!is_null($lineSettingBusiness)) {
            $channelSecret = $lineSettingBusiness->channel_secret; // Channel secret string
            //$httpRequestBody = collect($body)->toJson();

            //$httpRequestBody = json_encode($body, JSON_UNESCAPED_UNICODE); // SUPPORT UTF-8 
            $httpRequestBody = file_get_contents('php://input');         

            //$hash = hash_hmac('sha256', $httpRequestBody, trim($channelSecret), true);
            $hash = hash_hmac('sha256', $httpRequestBody, $channelSecret, true);
            $signature = base64_encode($hash);

            // Log::debug('header => '.$header .' <==> signature : status => '. $signature);
           
            if ( $header == $signature) {
                return true;
            } else {
                LineNotification::sentMessageLineNoti("YellowIdea Line Signature Not Match".'header => '.$header .' <==> signature : status => '. $signature);           
                return false;                  
            }           
        } else {
            return false;
        }
		
	}

	public static function getMessageEvent($datas)
    {
    	$type=false;

    	if (isset($datas['events'])) {
    		foreach ($datas['events'] as $arr) {
    			if (isset($arr['message']) && sizeof($arr['message']) > 0) {
                    // Log::debug('type => '.$arr['message']['type']);
    				if($arr['message']['type'] == 'location') {
       					$type = 'location';
    				} else if ($arr['message']['type'] == 'sticker') {
                        $type = 'sticker';
                    } else {
        				$type = $arr['type'];
    				}
				} else {
					$type = $arr['type'];
				}
    		}
    	}
    	return $type;
    }

    public static function getReceiverType($datas)
    {
    	$type=false;
        $roomId = false;
      	if (isset($arrs['events'])) {
            foreach ($arrs['events'] as $arr) {
                if (isset($arr['message']) && sizeof($arr['message']) > 0) {
                   //
                } else {
                    if (isset($arr['source'])) {
                        $roomId = $arr['source']['roomId'];
                        $type = $arr['source']['type'];
                    }
                }
            }
        }
    	return $type;
    }

    public static function eventFollow($body)
    {
    	$datas = collect();
    	if (isset($body['events'])) {
    		foreach ($body['events'] as $arr) {
    			if (isset($arr['message']) && sizeof($arr['message']) > 0) {
    				if($arr['message']['type'] == 'location') {
       					// Log::debug('Receive Location');
    				}
				}
				if (isset($arr['source'])) {
                    $datas->put('type', $arr['source']['type']);
                    if ($arr['source']['type'] == 'group') {
                    	$datas->put('groupId', $arr['source']['groupId']);
                    }
                    else if ($arr['source']['type'] == 'room') {
                    	$datas->put('roomId', $arr['source']['roomId']);
                    } else {
	            		$datas->put('userId', $arr['source']['userId']);
                    }
                } 
        		$datas->put('replyToken', $arr['replyToken']);
	            $datas->put('sourceType', $arr['source']['type']);
	            $datas->put('timestamp', $arr['timestamp']);

    		}
    		 $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/reply');
    		 $datas->put('token', 'Bearer vVWfd98+M9yVZT9du4UcFMCY4fSRSiSBsO+a1q9GCnrMwYRnSS/dkHMwPGkOgkhGqUOaewTC+KlJRjyLWzB/ARcwMu3xKA6kXzILFHliQE0bhORT/K4vKTUukRcKkVvfUEzChhB1Qn+EpeYl5uJqqQdB04t89/1O/w1cDnyilFU=');
    		
    		$arrMessage = [
	            [
	                "type"=>"text",
	                "text"=>"Thank For Added"
	            ],
	            [
	                "type"=>"text",
	                "text"=>"Sorry"
	            ],
	            [
	                "type"=>"text",
	                "text"=>"Developing"
	            ],
	        ];
	        $message = collect($arrMessage);

	        $data = collect([
                "replyToken" => $datas['replyToken'],
                "messages"   => $message
            ]);
    		$datas->put('data', $data->toJson());
        	
                    
            // Log::debug('Data =>'. $datas['data']);
        	
    		self::sent($datas);
    	}	
    }

    public static function eventJoin($body)
    {
    	$datas = collect();
    	if (isset($body['events'])) {
    		foreach ($body['events'] as $arr) {
    			if (isset($arr['message']) && sizeof($arr['message']) > 0) {
    				if($arr['message']['type'] == 'location') {
       					Log::debug('Receive Location');
    				}
				}
				if (isset($arr['source'])) {
                    $datas->put('type', $arr['source']['type']);
                    if ($arr['source']['type'] == 'group') {
                    	$datas->put('groupId', $arr['source']['groupId']);
                    }
                    else if ($arr['source']['type'] == 'room') {
                    	$datas->put('roomId', $arr['source']['roomId']);
                    } else {
	            		$datas->put('userId', $arr['source']['userId']);
                    }
                } 
        		$datas->put('replyToken', $arr['replyToken']);
	            $datas->put('sourceType', $arr['source']['type']);
	            $datas->put('timestamp', $arr['timestamp']);

    		}
    		 $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/reply');
    		 $datas->put('token', 'Bearer vVWfd98+M9yVZT9du4UcFMCY4fSRSiSBsO+a1q9GCnrMwYRnSS/dkHMwPGkOgkhGqUOaewTC+KlJRjyLWzB/ARcwMu3xKA6kXzILFHliQE0bhORT/K4vKTUukRcKkVvfUEzChhB1Qn+EpeYl5uJqqQdB04t89/1O/w1cDnyilFU=');
    		
    		$arrMessage = [
	            [
	                "type"=>"text",
	                "text"=>"Thank For Added"
	            ],
	            [
	                "type"=>"text",
	                "text"=>"Sorry"
	            ],
	            [
	                "type"=>"text",
	                "text"=>"Developing"
	            ],
	        ];
	        $message = collect($arrMessage);

	        $data = collect([
                "replyToken" => $datas['replyToken'],
                "messages"   => $message
            ]);
    		$datas->put('data', $data->toJson());
        	
                    
            // Log::debug('Data =>'. $datas['data']);
        	
    		self::sent($datas);
    	}	
    }

    public static function eventLeave($body)
    {
    	dd($body);
    }

    public static function sent($arrDatas)
    {
       // dd($arrDatas['data']);
        $datasReturn = [];
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $arrDatas['sentUrl'],
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $arrDatas['data'],
		  CURLOPT_HTTPHEADER => array(
		    "authorization: ".$arrDatas['token'],
		    "cache-control: no-cache",
		    "content-type: application/json; charset=UTF-8",
		  ),
		));

		$response = curl_exec($curl);
        // dd($response);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
            $datasReturn['result'] = 'E';
            $datasReturn['message'] = $err;
            Log::debug('cURL Error #: =>'. $err);                          
		} else {
            if($response == "{}"){
                $datasReturn['result'] = 'S';
                $datasReturn['message'] = 'Success';
            }else{
                $datasReturn['result'] = 'E';
                $datasReturn['message'] = $response;
            }
            
            Log::debug('Reply After eventFollow =>'. $response);
			Log::debug('return');     
		}

        return $datasReturn;
    }

    public static function autoReplyMessage($arrDatas, $dateStartNow = null)
    {
        $settingBot = SettingBot::first();
        $isGroup = 0;
        $isRoom = 0;
        try  {

        	$datas = collect();
        	if (isset($arrDatas['events'])) {
        		foreach ($arrDatas['events'] as $arr) {
    				if (isset($arr['message']) && sizeof($arr['message']) > 0) {
        				if($arr['message']['type'] == 'location') {
           					// Log::debug('Receive Location');
        				}  else if($arr['message']['type'] == 'text') {
                           $text  = $arr['message']['text'];
                        }                       
    				}
    				if (isset($arr['source'])) {
                        $datas->put('type', $arr['source']['type']);
                        if ($arr['source']['type'] == 'group') {
                        	$datas->put('groupId', $arr['source']['groupId']);
                            $datas->put('userId', $arr['source']['userId']);
                            $isGroup = 1;
                        } else if ($arr['source']['type'] == 'room') {
                        	$datas->put('roomId', $arr['source']['roomId']);
                            $datas->put('userId', $arr['source']['userId']);
                            $isRoom = 1;
                        } else  if ($arr['source']['type'] == 'user'){
                            $datas->put('userId', $arr['source']['userId']);                           
                        } else {
    	            		//   $datas->put('userId', $arr['source']['userId']);
                        }                       
                    } 
            		$datas->put('replyToken', $arr['replyToken']);
    	            $datas->put('sourceType', $arr['source']['type']);
    	            $datas->put('timestamp', $arr['timestamp']);                             
        		}
               
                $lineUserProfile = LineUserProfile::where('mid',$arr['source']['userId'])->first();

                // Comment By Aek 2017-05-29 LH Commit Close Function
                $checkConfirmation = ShareLocationConfirmation::checkConfirmation($text,$lineUserProfile);
                $checkCarouselConfirmation = CarouselConfirmation::checkConfirmation($text,$lineUserProfile);
                $settingBot = SettingBot::first();
                $checkQA = ConnectBotNoi::setDataQuestionAndAwnser($text);
                $botTrain = 0;
                $conf = 0;
                $botReply = null;
                $isKeyword = 0;

                if($isGroup == 1 || $isRoom == 1){
                    $keyword = BotJoinKeyword::where('keyword',$text)->first();
                }else{
                    $keyword = Keyword::where('keyword',$text)->where('active',1)->first();
                }
                if($keyword){
                    $isKeyword = 1;
                }

                Log::debug("Setting Bot => ".$settingBot->is_active);
                if($settingBot->is_active == 1 && $isKeyword != 1){
                    if($checkQA['result']){
                        // Log::debug("Train");
                        $botTrain = 1;
                        ConnectBotNoi::connectTrainBot($checkQA);
                        ConnectBotNoi::storeDataBotTrain($checkQA,$datas['userId']);
                        $messages = array();
                        $messages[]  = [
                            "type" =>"text",
                            "text" =>"ขอบคุณที่ช่วยชี้แนะครับ"
                        ];
                        $locationKeywords = collect();
                        $carouselItemKeywords = collect();                        
                    }else{
                        $replyBotnoi = ConnectBotNoi::connectReply(strtolower($text));
                        // \Log::debug($replyBotnoi);
                        if($replyBotnoi){
                            $conf = $replyBotnoi->conf;
                            $botReply = $replyBotnoi->resp;
                        }
                        
                        \Log::debug('conf => '.$conf.' botreply => '.$botReply);
                        $locationKeywords = LocationKeyword::where('keyword',$botReply)->groupby('location_id')->distinct()->get();
                        // $carouselItemKeywords = CarouselItemKeyword::where('keyword',$botReply)->groupby('carousel_id')->distinct()->get();
                        $carouselItemKeywords = CarouselItemKeyword::where('keyword',$botReply)->groupby('carousel_id')->distinct()->get();
                    }                   
                }else{
                    $locationKeywords = LocationKeyword::where('keyword',$text)->groupby('location_id')->distinct()->get();
                    $carouselItemKeywords = CarouselItemKeyword::where('keyword',$text)->groupby('carousel_id')->distinct()->get();
                }

                if($isGroup){
                    RecieveMessageGroup::create([
                        'line_user_id' => $lineUserProfile->id,
                        'group_id' => $arr['source']['groupId'],
                        'keyword'   =>  $arr['message']['text'],
                        'bot_conf'       =>  $conf,
                        'bot_reply'       =>  $botReply,
                    ]);
                }else if($isRoom){
                    RecieveMessageRoom::create([
                        'line_user_id' => $lineUserProfile->id,
                        'room_id' => $arr['source']['roomId'],
                        'keyword'   =>  $arr['message']['text'],
                        'bot_conf'       =>  $conf,
                        'bot_reply'       =>  $botReply,
                    ]);
                }else{
                    RecieveMessage::create([
                        'keyword'   =>  $arr['message']['text'],
                        'mid'       =>  $datas['userId'],
                        'line_biz_con_id'   =>  ($lineUserProfile->lineBizConCustomer)? $lineUserProfile->lineBizConCustomer->id : 0,
                        'bot_conf'       =>  $conf,
                        'bot_reply'       =>  $botReply,
                    ]);
                }

                if($isKeyword == 0 && $botReply != ""){
                    // $keyword = Keyword::where('keyword',$botReply)->where('active',1)->first();
                    if($isGroup == 1 || $isRoom == 1){
                        $keyword = BotJoinKeyword::where('keyword',$botReply)->first();
                    }else{
                        $keyword = Keyword::where('keyword',$botReply)->where('active',1)->first();
                    }
                }
                
        		if (is_null($keyword) && $locationKeywords->count() == 0 && $checkConfirmation == 0 && $carouselItemKeywords->count() == 0 && $checkCarouselConfirmation == 0 && $botTrain == 0) {
        			//default
                    // auto_default:
                        
                        // Log::debug($checkQA['result']);
                        if($conf > $settingBot->conf){
                            if($checkQA['result'] != 1){
                                $messages = array();
                                $messages[]  = [
                                    "type" =>"text",
                                    "text" => $replyBotnoi->resp
                                ];
                            }                           
                        }else{

                            $messages = self::setAutoreplyDefault($lineUserProfile,$isGroup,$isRoom);
                        }                       
        		} else if ($carouselItemKeywords->count() > 0 && is_null($keyword) && $checkCarouselConfirmation == 0 && $botTrain == 0) {
                    Log::debug("Carousel");
                    if($conf > $settingBot->conf || $settingBot->is_active == 0){
                        $confirmationSettingCarousel = ConfirmationSettingCarousel::first();
                        if($carouselItemKeywords->count() > 0){
                            $carouselItemKeywords = $carouselItemKeywords->take($confirmationSettingCarousel->max_display);
                        }
                        $carouselKeywordCount = $carouselItemKeywords->count();
                        if($carouselKeywordCount > 5){
                            Log::debug("Multiple Carousel");
                            $createDataConfirmation = CarouselConfirmation::createDataConfirmation($carouselItemKeywords,$lineUserProfile);
                            $confirmation = self::setDataConfirmation();
                            $corosel = self::setCarouselLogic($carouselItemKeywords->take(5));
                            $messages = [$corosel,$confirmation];                           
                        }else{
                            Log::debug("Single Carousel");
                            $corosel = self::setCarouselLogic($carouselItemKeywords->take(5));
                            $messages = [$corosel];
                        }                        
                    }else{
                        // \Log::debug('default keyword conf < 0.7');
                        // $carouselDefault2 = self::setDefaultCarousel2();
                        
                        // $carouselDefault = self::setDefaultCarousel();
                        // $messages = $carouselDefault;
                        $messages = self::setAutoreplyDefault($lineUserProfile,$isGroup,$isRoom);
                    }                    
                } else if ($checkCarouselConfirmation == 1 && $botTrain == 0) {
                    Log::debug('Re Confirmation Carousel');
                    $carouselConfirmation = CarouselConfirmation::where('line_user_id',$lineUserProfile->id)->where('end_time',null)->first();
                    $countCarouselConfirmationItem = $carouselConfirmation->confirmationItems->count();
                    if($countCarouselConfirmationItem > 5){
                        $confirmation = self::setDataConfirmation();
                        $carousel = self::setCoroselLogicByConfirmation($carouselConfirmation);
                        $messages = [$carousel,$confirmation];                       
                    }else{
                        $carousel = self::setCoroselLogicByConfirmation($carouselConfirmation);
                        $messages = [$carousel];
                    }                    
                } else if ($locationKeywords->count() > 0 && is_null($keyword) && $checkConfirmation == 0 && $botTrain == 0) {
                    Log::debug('incase mapping location');
                    // $messages = array();
                    if($conf > 0){
                        $locationKeywordCount = $locationKeywords->count();
                        if($locationKeywordCount > 5){
                            $createDataConfirmation = CoroselConfirmation::createDataConfirmation($locationKeywords,$lineUserProfile);
                            $confirmation = self::setDataConfirmation();
                            $corosel = self::setCorosel($locationKeywords->take(5));
                            $messages = [$corosel,$confirmation];                          
                        }else{
                            $corosel = self::setCorosel($locationKeywords->take(5));
                            $messages = [$corosel];
                        }                       
                    }else{
                        \Log::debug('default keyword conf < 0.7');
                        
                        $carouselDefault = self::setDefaultCarousel();
                        $messages = $carouselDefault;
                    }
                    // $messages = self::setDataConfirmation();
                    // Log::debug($messages);                 
                } else if ($checkConfirmation == 1 && $botTrain == 0) {
                    Log::debug('Re Confirmation');
                    $coroselConfirmation = ShareLocationConfirmation::where('line_user_id',$lineUserProfile->id)->where('end_time',null)->first();
                    $countCoroselConfirmationItem = $coroselConfirmation->shareLocationConfimationItems->count();
                    if($countCoroselConfirmationItem > 5){
                        $confirmation = self::setDataConfirmationShareLocation();
                        $corosel = self::setCoroselLogicShareLocationByConfirmation($coroselConfirmation);
                        $messages = [$corosel,$confirmation];                       
                    }else{
                        $corosel = self::setCoroselLogicShareLocationByConfirmation($coroselConfirmation);
                        $messages = [$corosel];
                    }                 
                } else if ($botTrain == 1) {
                } else {
        			$autoReplyKeyWord  = $keyword->autoReplyKeyWord;
                    if(!is_null($autoReplyKeyWord)) {
                        if ($autoReplyKeyWord->active) {
                            $items  = $autoReplyKeyWord->autoReplyKeyWordItems;
                            // Log::debug($items);
                            $messages = array();
                            if (sizeof($items) > 0) {
                                foreach ($items as $item) {
                                    if ($item->messageType->type == 'text') {
                                        $messages[]  = [
                                            "type" =>"text",
                                            "text" => AutoReplyKeywordMessage::encodeMessageEmo($item->message->message,$lineUserProfile)
                                        ];                                       
                                    }  elseif ($item->messageType->type == 'sticker') {
                                        $messages[]  = [
                                            "type" =>"sticker",
                                            "packageId" => (string) $item->sticker->packageId,
                                            "stickerId" => (string) $item->sticker->stickerId,
                                        ];                                       
                                    } elseif ($item->messageType->type == 'imagemap') {
                                        Log::debug('incase richmessage');
                                        // $messages = array();
                                        $messages[] = self::setImagemap($item->auto_reply_richmessage_id);
                                        // Log::debug($messages);
                                    } elseif ($item->messageType->type == 'template_message') {
                                        $messages[] = TemplateMessageCoreFunction::setTemplateMessage($item->template_message_id);                               
                                    }
                                }
                            }                              
                        } else {
                            $messages = self::setAutoreplyDefault($lineUserProfile,$isGroup,$isRoom);
                            // goto auto_default;
                        }                       
                    } else {
                        $messages = self::setAutoreplyDefault($lineUserProfile,$isGroup,$isRoom);
                        // goto auto_default;
                    }
        		}
                
                if (isset($messages) && sizeof($messages) > 0) {
                    $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();

            		$datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);
            		
            		$arrMessage = [
            			$messages
        	        ];
        	        $message = collect($messages);

                    $now = Carbon::now();
                    $dateNow2 = $now;
                    
                    //Change Reply to Push   fix =>
                    if(!is_null($dateStartNow) && $dateStartNow->diffInSeconds($dateNow2) >= 7 ) {
                        Log::debug('use push');
                        $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/push');
                        if ($datas['sourceType'] == 'user') {
                            $data = collect([
                                "to" => $datas['userId'],
                                "replyToken" => $datas['replyToken'], // for test
                                "messages"   => $message
                            ]); 

                            PushOrReply::create([
                                'mid' => $datas['userId'],
                                'action' => 'push',
                                'type' => 'Auto Reply'
                            ]);
                        }
                    } else {
                        Log::debug('use reply');
                        $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/reply');
                        $data = collect([
                            "replyToken" => $datas['replyToken'],
                            "messages"   => $message
                        ]); 

                        PushOrReply::create([
                            'mid' => $datas['userId'],
                            'action' => 'reply',
                            'type' => 'Auto Reply'
                        ]);        
                    }
                    // Log::debug($data);

                    $datas->put('data', $data->toJson());
            		self::sent($datas);                
                }
        	}

        } catch(Exception $e) {
            Log::debug('Error '.$e);
        }    
    }

    public static function defalutSticker($arrDatas)
    {
        $datas = collect();
        if (isset($arrDatas['events'])) {
            foreach ($arrDatas['events'] as $arr) {
                if (isset($arr['message']) && sizeof($arr['message']) > 0) {
                    if($arr['message']['type'] == 'location') {
                        Log::debug('Receive Location');
                    }  else if($arr['message']['type'] == 'text') {
                       $text  = $arr['message']['text'];
                    }
                }

                if (isset($arr['source'])) {
                    $datas->put('type', $arr['source']['type']);
                    if ($arr['source']['type'] == 'group') {
                        $datas->put('groupId', $arr['source']['groupId']);
                    } else if ($arr['source']['type'] == 'room') {
                        $datas->put('roomId', $arr['source']['roomId']);
                    } else {
                        //   $datas->put('userId', $arr['source']['userId']);
                    }
                } 
                $datas->put('replyToken', $arr['replyToken']);
                $datas->put('sourceType', $arr['source']['type']);
                $datas->put('timestamp', $arr['timestamp']);
            }
            $messages[]  = [
                "type" =>"text",
                "text" => "Sticker สวยจัง"
            ];

            $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();

            $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/reply');
            $datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);
            
            $arrMessage = [
                $messages
            ];
            $message = collect($messages);

            $data = collect([
                "replyToken" => $datas['replyToken'],
                "messages"   => $message
            ]);
            $datas->put('data', $data->toJson());
            // Log::debug('Data =>'. $datas['data']);
            
            self::sent($datas);
        }

    }

    public static function setImagemap($richMessageID)
    {
          $richMessageMain = Richmessage::find($richMessageID);
          // $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();
          // $datas = collect();
          $messages = collect([
            "type" => "imagemap",
            "baseUrl" => $richMessageMain->rich_message_url,
            "altText" => $richMessageMain->alt_text,
            "baseSize" => [
                "height" => ($richMessageMain->height <= 2040)? $richMessageMain->height : "2040",
                "width" => "1040",
            ],
          ]);
          foreach ($richMessageMain->areas as $area) {
            $action = $area->action;
            $bound = $area->bound;
            $height = ceil($bound->height)*2;
            $width = ceil($bound->width)*2;
            $x = ceil($bound->x)*2;
            $y = ceil($bound->y)*2;
            if($action){
                if($action->type == 'url_and_other_action'){
                  $dataItems[] = [
                    "type"  => "uri",
                    "linkUri"  => $action->data,
                    "area"  => [
                      "x" => $x,
                      "y" => $y,
                      "width" => $width,
                      "height" => $height,
                    ],
                  ];
                }
                if($action->type == 'keyword'){
                  $dataItems[] = [
                    "type" => "message",
                    "text" => $action->data,
                    "area"  => [
                      "x" => $x,
                      "y" => $y,
                      "width" => $width,
                      "height" => $height,
                    ],
                  ];
                }
                if($action->type == 'share_location_action'){
                  $dataItems[] = [
                    "type"  => "uri",
                    "linkUri"  => "line://nv/location",
                    "area"  => [
                      "x" => $x,
                      "y" => $y,
                      "width" => $width,
                      "height" => $height,
                    ],
                  ];
                }
                if($action->type == 'no-action' || $action->type == -1){
                  $dataItems[] = [
                    "type" => "uri",
                    "linkUri" => "http://#",
                    "area"  => [
                      "x" => 0,
                      "y" => 0,
                      "width" => 1,
                      "height" => 1,
                    ],
                  ];
                }
                if($action->type == 'post_back'){
                  $dataItems[] = [
                    "type" => "message",
                    "text" => $action->data,
                    "area"  => [
                      "x" => $x,
                      "y" => $y,
                      "width" => $width,
                      "height" => $height,
                    ],
                  ];
                }
            }
          }
          $messages->put('actions',$dataItems);

          return $messages;
    }

    public static function setCoroselLogicShareLocationByConfirmation($confirmation)
    {
        $altText = $confirmation->shareLocationConfimationItems->first()->shareLocationItem->shareLocation->alt_text;
        $countConfirmationItem = $confirmation->shareLocationConfimationItems->count();
        if(($countConfirmationItem - 5) < 0){
            $confirmation->update([
                'end_time' => \Carbon\Carbon::now(),
            ]);
        }
        $messages = collect([
            "type" => "template",
            "altText" => $altText,
        ]);
        $templates = collect([
            "type" => "carousel",
        ]);
        foreach ($confirmation->shareLocationConfimationItems->take(5) as $key => $confirmationItem) {
            $carouselItem = $confirmationItem->shareLocationItem;
            $carousel = $carouselItem->shareLocation;
            $countLengthDesc = strlen($carouselItem->description);
            if($countLengthDesc > 60){
              $desc = mb_substr($carouselItem->description, 0,59).'';
            }else{
              $desc = $carouselItem->description;
            }
            $columns[] = [
              "thumbnailImageUrl" => $carouselItem->image_url,
              "title" => $carouselItem->name,
              "text" => $desc,
            ];
            $actions = [];
            if($carousel->action_1 != ""){
              $data = self::setDataTypeCarousel(1,$carousel->action_1,$carousel->label_1,$carouselItem);
              if(count($data) > 0){
                $actions[] = $data;
              }
            }
            if($carousel->action_2 != ""){
              $data = self::setDataTypeCarousel(2,$carousel->action_2,$carousel->label_2,$carouselItem);
              if(count($data) > 0){
                $actions[] = $data;
              }
            }
            if($carousel->action_3 != ""){
              $data = self::setDataTypeCarousel(3,$carousel->action_3,$carousel->label_3,$carouselItem);
              if(count($data) > 0){
                $actions[] = $data;
              }
            }
            $columns[$key]['actions'] = $actions;
            $confirmationItem->update([
                'is_active' => 1
            ]);
        }

        // dd($columns);
          
        $templates->put('columns',$columns);
        // $columns->put('actions',$actions);
        $messages->put('template',$templates);

        return $messages;
    }

    public static function setCoroselLogicByConfirmation($confirmation)
    {
        $altText = $confirmation->confirmationItems->first()->carouselItem->carousel->alt_text;
        $countConfirmationItem = $confirmation->confirmationItems->count();
        if(($countConfirmationItem - 5) < 0){
            $confirmation->update([
                'end_time' => \Carbon\Carbon::now(),
            ]);
        }
        $messages = collect([
            "type" => "template",
            "altText" => $altText,
        ]);
        $templates = collect([
            "type" => "carousel",
        ]);
        foreach ($confirmation->confirmationItems->take(5) as $key => $confirmationItem) {
            $carouselItem = $confirmationItem->carouselItem;
            $carousel = $carouselItem->carousel;
            $countLengthDesc = strlen($carouselItem->description);
            $countLengthTitle = strlen($carouselItem->name);
            if($countLengthDesc > 60){
              $desc = mb_substr($carouselItem->description, 0,59).'';
            }else{
              $desc = $carouselItem->description;
            }

            if($countLengthTitle > 40){
              $title = mb_substr($carouselItem->name, 0,39).'';
            }else{
              $title = $carouselItem->name;
            }
            $columns[] = [
              "thumbnailImageUrl" => $carouselItem->image_url,
              "title" => $title,
              "text" => $desc,
            ];
            $actions = [];
            if($carousel->action_1 != ""){
              $data = self::setDataTypeCarousel(1,$carousel->action_1,$carousel->label_1,$carouselItem);
              if(count($data) > 0){
                $actions[] = $data;
              }
            }
            if($carousel->action_2 != ""){
              $data = self::setDataTypeCarousel(2,$carousel->action_2,$carousel->label_2,$carouselItem);
              if(count($data) > 0){
                $actions[] = $data;
              }
            }
            if($carousel->action_3 != ""){
              $data = self::setDataTypeCarousel(3,$carousel->action_3,$carousel->label_3,$carouselItem);
              if(count($data) > 0){
                $actions[] = $data;
              }
            }
            $columns[$key]['actions'] = $actions;
            $confirmationItem->update([
                'is_active' => 1
            ]);
        }

        // dd($columns);
          
        $templates->put('columns',$columns);
        // $columns->put('actions',$actions);
        $messages->put('template',$templates);

        return $messages;
    }

    public static function setDataConfirmation()
    {
        $confirmationSettingCarousel = ConfirmationSettingCarousel::first();
        $messages = collect([
            "type" => "template",
            "altText" => $confirmationSettingCarousel->alt_text,
        ]);
        $templates = collect([
            "type" => "confirm",
            "text" => $confirmationSettingCarousel->name,
        ]);
        $actions = [];
        $actions[] = [
            "type" => "message",
            "label" => $confirmationSettingCarousel->confimation_yes,
            "text" => $confirmationSettingCarousel->confimation_yes
        ];
        $actions[] = [
            "type" => "message",
            "label" => $confirmationSettingCarousel->confimation_no,
            "text" => $confirmationSettingCarousel->confimation_no
        ];

        $templates->put('actions',$actions);
        $messages->put('template',$templates);

        return $messages;
    }

    public static function setDataConfirmationShareLocation()
    {
        $confirmationSettingShareLocation = ConfirmationSettingShareLocation::first();
        $messages = collect([
            "type" => "template",
            "altText" => $confirmationSettingShareLocation->alt_text,
        ]);
        $templates = collect([
            "type" => "confirm",
            "text" => $confirmationSettingShareLocation->name,
        ]);
        $actions = [];
        $actions[] = [
            "type" => "message",
            "label" => $confirmationSettingShareLocation->confimation_yes,
            "text" => $confirmationSettingShareLocation->confimation_yes
        ];
        $actions[] = [
            "type" => "message",
            "label" => $confirmationSettingShareLocation->confimation_no,
            "text" => $confirmationSettingShareLocation->confimation_no
        ];

        $templates->put('actions',$actions);
        $messages->put('template',$templates);

        return $messages;
    }

    public static function shareLocation($arrDatas, $dateStartNow = null)
    {
        $confirmationSettingShareLocation = ConfirmationSettingShareLocation::first();
        $datas = collect();
        foreach ($arrDatas['events'] as $arr) {
            if (isset($arr['message']) && sizeof($arr['message']) > 0) {
                if($arr['message']['type'] == 'location') {
                    // Log::debug('Receive Location');
                }  else if($arr['message']['type'] == 'text') {
                   $text  = $arr['message']['text'];
                }
            }
            if (isset($arr['source'])) {
                $datas->put('type', $arr['source']['type']);
                if ($arr['source']['type'] == 'group') {
                    $datas->put('groupId', $arr['source']['groupId']);
                } else if ($arr['source']['type'] == 'room') {
                    $datas->put('roomId', $arr['source']['roomId']);
                } else  if ($arr['source']['type'] == 'user'){
                    $datas->put('userId', $arr['source']['userId']);
                } else {
                    //   $datas->put('userId', $arr['source']['userId']);
                }
            } 
            $datas->put('replyToken', $arr['replyToken']);
            $datas->put('sourceType', $arr['source']['type']);
            $datas->put('timestamp', $arr['timestamp']);
        }

        $message = $arrDatas['events'][0]['message'];
        $lat = (float) $arrDatas['events'][0]['message']['latitude'];
        $lng = (float) $arrDatas['events'][0]['message']['longitude'];
        $address = $arrDatas['events'][0]['message']['address'];
        $unit = 6378.10;
        $radius = (double) 1000;
        $locationItems = ShareLocationItem::having('distance','<=',$radius)
          ->select(\DB::raw("*,
                      ($unit * ACOS(COS(RADIANS($lat))
                          * COS(RADIANS(latitude))
                          * COS(RADIANS($lng) - RADIANS(longtitude))
                          + SIN(RADIANS($lat))
                          * SIN(RADIANS(latitude)))) AS distance")
          )->orderBy('distance','asc')->get();
        if($locationItems->count() > 0){
            $locationItems = $locationItems->take($confirmationSettingShareLocation->max_display);
        }

        $locationKeywordCount = $locationItems->count();
        $lineUserProfile = LineUserProfile::where('mid',$datas['userId'])->first();
        UserShareLocation::create([
            'line_user_id' => $lineUserProfile->id,
            'latitude' => $lat,
            'longtitude' => $lng,
            'address' => $address,
        ]);
        if($locationKeywordCount > 5){
            $createDataConfirmation = ShareLocationConfirmation::createDataConfirmationShareLocation($locationItems->sortBy('distance'),$lineUserProfile);
            $confirmation = self::setDataConfirmationShareLocation();
            $corosel = self::setCarouselLogicByShareLocation($locationItems->sortBy('distance')->take(5));
            $messages = [$corosel,$confirmation];
        }else{
            $corosel = self::setCarouselLogicByShareLocation($locationItems->sortBy('distance')->take(5));
            $messages = [$corosel];
        }

        // $messages = self::setCoroselByShareLocation($locationItems->take(5));


        $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();

        $datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);
        
        $arrMessage = [
            $messages
        ];
        $message = collect($messages);

        $now = Carbon::now();
        $dateNow2 = $now;
        
        //Change Reply to Push   fix =>
        if(!is_null($dateStartNow) && $dateStartNow->diffInSeconds($dateNow2) >= 5 ) {
            Log::debug('use   push ');
            $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/push');
            if ($datas['sourceType'] == 'user') {
                $data = collect([
                    "to" => $datas['userId'],
                    "replyToken" => $datas['replyToken'], // for test
                    "messages"   => $message
                ]);

                PushOrReply::create([
                    'mid' => $datas['userId'],
                    'action' => 'push',
                    'type' => 'Share Location'
                ]);
            }
        } else {
            Log::debug('use reply');
            $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/reply');
            $data = collect([
                "replyToken" => $datas['replyToken'],
                "messages"   => $message,
            ]);

            PushOrReply::create([
                'mid' => $datas['userId'],
                'action' => 'reply',
                'type' => 'Share Location'
            ]);
        }
        // Log::debug($data);

        $datas->put('data', $data->toJson());
        //$datas->put('dateStartNow', $dateStartNow);
        // Log::debug('Second = > '.$dateNow2.'  =>  diff => '.$dateStartNow->diffInSeconds($dateNow2));
        // Log::debug('Data =>'. $datas['data']);
        self::sent($datas);
        // $locationKeywords = LocationItem::->get();
        // Log::debug($message);
    }

    public static function getUserProfile($mid)
    {
        $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();
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

    public static function storeLineUserProfile($mid)
    {
        $lineUserProfile = LineUserProfile::where('mid',$mid)->first();
        // GreetingMessage::sendGreeting($mid);
        $userProfile = self::getUserProfile($mid);
        Log::debug($userProfile);
        $displayName = (array_key_exists('displayName', $userProfile))? $userProfile['displayName'] : null;
        $pictureUrl = (array_key_exists('pictureUrl', $userProfile))? $userProfile['pictureUrl'] : null;
        if($lineUserProfile == ''){
            $lineUserProfile = LineUserProfile::create([
                'mid' => $mid,
                'name' => $displayName,
                'avatar' => $pictureUrl,
                'flag_status' => 'normal',
            ]);
        }else{
            if($displayName != $lineUserProfile->name || $pictureUrl != $lineUserProfile->avatar){
                $lineUserProfile->update([
                    'name' => $displayName,
                    'avatar' => $pictureUrl,
                ]);
            }
        }

        return $lineUserProfile;
    }

    public static function setCarouselLogicByShareLocation($carouselItems)
    {
        $altText = $carouselItems->first()->shareLocation->alt_text;
        $messages = collect([
            "type" => "template",
            "altText" => $altText,
        ]);
        $templates = collect([
            "type" => "carousel",
        ]);
        foreach ($carouselItems as $key => $carouselItem) {
            // Log::debug('distance => '.$carouselItem->distance);
            $carousel = $carouselItem->shareLocation;
            $countLengthDesc = strlen($carouselItem->description);
            $countLengthTitle = strlen($carouselItem->name);
            if($countLengthDesc > 60){
              $desc = mb_substr($carouselItem->description, 0,59).'';
            }else{
              $desc = $carouselItem->description;
            }

            if($countLengthTitle > 40){
              $title = mb_substr($carouselItem->name, 0,39).'';
            }else{
              $title = $carouselItem->name;
            }
            $columns[] = [
              "thumbnailImageUrl" => $carouselItem->image_url,
              "title" => $title,
              "text" => $desc,
            ];
            $actions = [];
            if($carousel->action_1 != ""){
              $data = self::setDataTypeCarousel(1,$carousel->action_1,$carousel->label_1,$carouselItem);
              if(count($data) > 0){
                $actions[] = $data;
              }
            }
            if($carousel->action_2 != ""){
              $data = self::setDataTypeCarousel(2,$carousel->action_2,$carousel->label_2,$carouselItem);
              if(count($data) > 0){
                $actions[] = $data;
              }
            }
            if($carousel->action_3 != ""){
              $data = self::setDataTypeCarousel(3,$carousel->action_3,$carousel->label_3,$carouselItem);
              if(count($data) > 0){
                $actions[] = $data;
              }
            }
            $columns[$key]['actions'] = $actions;
        }

        // dd($columns);
          
        $templates->put('columns',$columns);
        // $columns->put('actions',$actions);
        $messages->put('template',$templates);

        return $messages;
    }

    public static function setCarouselLogic($carouselKeywords)
    {
        $altText = $carouselKeywords->first()->carouselItem->carousel->alt_text;
        $messages = collect([
            "type" => "template",
            "altText" => $altText,
        ]);
        $templates = collect([
            "type" => "carousel",
        ]);
        foreach ($carouselKeywords as $key => $carouselKeyword) {
            $carouselItem = $carouselKeyword->carouselItem;
            $carousel = $carouselItem->carousel;
            $countLengthDesc = strlen($carouselItem->description);
            $countLengthTitle = strlen($carouselItem->name);
            if($countLengthDesc > 60){
              $desc = mb_substr($carouselItem->description, 0,59).'';
            }else{
              $desc = $carouselItem->description;
            }

            if($countLengthTitle > 40){
              $title = mb_substr($carouselItem->name, 0,39).'';
            }else{
              $title = $carouselItem->name;
            }
            $columns[] = [
              "thumbnailImageUrl" => $carouselItem->image_url,
              "title" => $title,
              "text" => $desc,
            ];
            $actions = [];
            if($carousel->action_1 != ""){
              $data = self::setDataTypeCarousel(1,$carousel->action_1,$carousel->label_1,$carouselItem);
              if(count($data) > 0){
                $actions[] = $data;
              }
            }
            if($carousel->action_2 != ""){
              $data = self::setDataTypeCarousel(2,$carousel->action_2,$carousel->label_2,$carouselItem);
              if(count($data) > 0){
                $actions[] = $data;
              }
            }
            if($carousel->action_3 != ""){
              $data = self::setDataTypeCarousel(3,$carousel->action_3,$carousel->label_3,$carouselItem);
              if(count($data) > 0){
                $actions[] = $data;
              }
            }
            $columns[$key]['actions'] = $actions;
        }

        // dd($columns);
          
        $templates->put('columns',$columns);
        // $columns->put('actions',$actions);
        $messages->put('template',$templates);

        return $messages;
    }

    public static function setDataTypeCarousel($actionNumber,$type,$label,$carouselItem)
    {
        if($actionNumber == 1){
            $linkUrl = $carouselItem->link_url_1;
            $tel = $carouselItem->tel_1;
            $keywordText = $carouselItem->keyword_text_1;
        }else if($actionNumber == 2){
            $linkUrl = $carouselItem->link_url_2;
            $tel = $carouselItem->tel_2;
            $keywordText = $carouselItem->keyword_text_2;
        }else{
            $linkUrl = $carouselItem->link_url_3;
            $tel = $carouselItem->tel_3;
            $keywordText = $carouselItem->keyword_text_3;
        }
        $actions = [];
        if($type == "Web Link URL"){
            if ($linkUrl != "") {
                $actions = [
                  "type" => "uri",
                  "label" => $label,
                  "uri" => $linkUrl
                ];
            }
        }else if($type == "Keyword"){
            if($keywordText != ""){
                $actions = [
                  "type" => "message",
                  "label" => $label,
                  "text" => $keywordText
                ];
            }
        }else if($type == "Tel"){
            if($tel != ""){
                $actions = [
                  "type" => "uri",
                  "label" => $label,
                  "uri" => 'tel:'.$tel
                ];
            }
        }else{
            if($carouselItem->latitude != "" && $carouselItem->longtitude != ""){
                $actions = [
                  "type" => "uri",
                  "label" => $label,
                  "uri" => 'https://www.google.com/maps/dir//'.$carouselItem->latitude.','.$carouselItem->longtitude
                ];
            }
        }

        return $actions;
    }

    public static function sendImage($imagePath,$mid)
    {
        $lineSettingBusiness = \YellowProject\LineSettingBusiness::where('active',true)->first();
        $datas = collect();
        $datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);
        $messages = array();
        
        $messages[] = [
            "type" => "image",
            "originalContentUrl" => $imagePath,
            "previewImageUrl" => $imagePath,
        ];

        $message = collect($messages);
            
        $data = collect([
            "to" => $mid,
            "messages"   => $message
        ]);

        $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/push');
        $datas->put('data', $data->toJson());
        self::sent($datas);
    }

    public static function setAutoreplyDefault($lineUserProfile,$isGroup,$isRoom)
    {
        $autoReplyDefault = AutoReplyDefault::where('active',1)->first();
        $messages = array();
        if($autoReplyDefault && !$isGroup && !$isRoom){

            $items  = $autoReplyDefault->autoReplyDefaultItems;
            if (sizeof($items) > 0) {
                foreach ($items as $key => $item) {
                    if ($item->messageType->type == 'text') {
                        $messages[$key]  = [
                            "type" =>"text",
                            "text" => AutoReplyDefaultMessage::encodeMessageEmo($item->message->message,$lineUserProfile)
                        ];
                    } elseif ($item->messageType->type == 'sticker') {
                        $messages[$key]  = [
                            "type" =>"sticker",
                            "packageId" => ''.$item->sticker->packageId.'',
                            "stickerId" => ''.$item->sticker->stickerId.'',
                        ];
                    } elseif ($item->messageType->type == 'imagemap'){
                        $richMessageMain = RichMessageMain::find($item->auto_reply_richmessage_id);
                        $messages[$key] = [
                            "type" => "imagemap",
                            "baseUrl" => $richMessageMain->rich_message_url,
                            "altText" => $richMessageMain->alt_text,
                            "baseSize" => [
                                "height" => "1024",
                                "width" => "1024",
                            ],
                        ];
                        foreach ($richMessageMain->richMessageItems as $richMessageDatas) {
                            if($richMessageDatas->action == 'url'){
                              $dataItems[] = [
                                "type"  => "uri",
                                "linkUri"  => $richMessageDatas->url_protocal.$richMessageDatas->url_value,
                                "area"  => [
                                  "x" => $richMessageDatas->x,
                                  "y" => $richMessageDatas->y,
                                  "width" => $richMessageDatas->width,
                                  "height" => $richMessageDatas->height,
                                ],
                              ];
                            }
                            if($richMessageDatas->action == 'keyword'){
                              $dataItems[] = [
                                "type" => "message",
                                "text" => $richMessageDatas->keyword,
                                "area"  => [
                                  "x" => $richMessageDatas->x,
                                  "y" => $richMessageDatas->y,
                                  "width" => $richMessageDatas->width,
                                  "height" => $richMessageDatas->height,
                                ],
                              ];
                            }
                            if($richMessageDatas->action == 'no-action'){
                              $dataItems[] = [
                                "type" => "uri",
                                "linkUri" => "http://#",
                                "area"  => [
                                  "x" => 0,
                                  "y" => 0,
                                  "width" => 1,
                                  "height" => 1,
                                ],
                              ];
                            }
                        }
                        $messages[$key]['actions'] = $dataItems;
                    }
                }
            }
        }
        return $messages;
    }

    public static function sentMessageDefaultURL($arrDatas, $dateStartNow = null)
    {
        $datas = collect();
        foreach ($arrDatas['events'] as $arr) {
            if (isset($arr['message']) && sizeof($arr['message']) > 0) {
                if($arr['message']['type'] == 'location') {
                    // Log::debug('Receive Location');
                }  else if($arr['message']['type'] == 'text') {
                   $text  = $arr['message']['text'];
                }
            }
            if (isset($arr['source'])) {
                $datas->put('type', $arr['source']['type']);
                if ($arr['source']['type'] == 'group') {
                    $datas->put('groupId', $arr['source']['groupId']);
                } else if ($arr['source']['type'] == 'room') {
                    $datas->put('roomId', $arr['source']['roomId']);
                } else  if ($arr['source']['type'] == 'user'){
                    $datas->put('userId', $arr['source']['userId']);
                } else {
                    //   $datas->put('userId', $arr['source']['userId']);
                }
            } 
            $datas->put('replyToken', $arr['replyToken']);
            $datas->put('sourceType', $arr['source']['type']);
            $datas->put('timestamp', $arr['timestamp']);
        }


        $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();

        $datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);

        $messages[]  = [
            "type" =>"text",
            "text" => "กรุณาส่งเป็นข้อความ text",
        ];
        
        $message = collect($messages);

        $now = Carbon::now();
        $dateNow2 = $now;
        
        if(!is_null($dateStartNow) && $dateStartNow->diffInSeconds($dateNow2) >= 5 ) {
            Log::debug('use   push ');
            $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/push');
            if ($datas['sourceType'] == 'user') {
                $data = collect([
                    "to" => $datas['userId'],
                    "replyToken" => $datas['replyToken'], // for test
                    "messages"   => $message
                ]);
            }
        } else {
            Log::debug('use   reply ');
            $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/reply');
            $data = collect([
                "replyToken" => $datas['replyToken'],
                "messages"   => $message
            ]);
        }

        $datas->put('data', $data->toJson());
        self::sent($datas);
    }

    public static function refreshToken()
    {
        $lineSettingBusiness = \YellowProject\LineSettingBusiness::where('active',true)->first();
        $curl = curl_init();
 
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.line.me/v2/oauth/accessToken",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 300,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id=".$lineSettingBusiness->channel_id."&client_secret=".$lineSettingBusiness->channel_secret,
          
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded",
            "postman-token: e23cf0e9-5e65-b4c2-bce5-9771df9ce58f"
          ),
        ));
 
        $response = curl_exec($curl);
        $err = curl_error($curl);
 
        curl_close($curl);
 
        if ($err) {
          // echo "cURL Error #:" . $err;
        } else {
          // echo $response;
        }
 
        return json_decode($response);
    }

    public static function clearDataCarouselConfirmation($lineUserProfile)
    {
        $datenow = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        $carouselConfirmation = CarouselConfirmation::where('line_user_id',$lineUserProfile->id)->where('end_time',null);

        $carouselConfirmation->update([
            'end_time' => $datenow
        ]);
    }
    
}
