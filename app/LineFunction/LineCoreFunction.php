<?php

namespace YellowProject\LineFunction;

use Illuminate\Database\Eloquent\Model;
use Log;
use YellowProject\LineWebHooks;
use YellowProject\GreetingMessage;
use YellowProject\AutoReplyDefault;
use YellowProject\LineUserProfile;
use YellowProject\HistoryAddBlock;
use YellowProject\GeneralFunction\CoreFunction;
use YellowProject\Eplus\CoreLineFunction\CoreLineFunction as EplusCustomerCoreLineFucntion;
use YellowProject\Eplus\Salesman\CoreLineFunction\CoreLineFunction as EplusSalesmanCoreLineFunction;
use YellowProject\TemplateMessage\CoreFunction as TemplateMessageCoreFunction;
use Carbon\Carbon;

class LineCoreFunction extends Model
{
    public static function lineWebhookCoreFunction($header,$body,$dateNow = null)
    {
    	$now = Carbon::now();
        $dateNowStart = ($dateNow != null)? $dateNow : null;

        try  {
	    	$mid = $body['events'][0]['source']['userId'];
	    	$type = $body['events'][0]['type'];
	        $lineUserProfile = LineWebHooks::storeLineUserProfile($mid);
	        // $userProfile = GreetingMessage::getUserProfile($mid);
	        $lineUserProfile = LineUserProfile::where('mid',$mid)->first();

	        if($type == 'unfollow'){
	            Log::debug('unfollow');
	            HistoryAddBlock::create([
	                'line_user_id' => $lineUserProfile->id,
	                'action' => 'unfollow'
	            ]);
	            $lineUserProfile->update([ 'is_follow' => 0 ]);
	        }
	        
	        if($type == 'follow'){
	            Log::debug('follow');
	            HistoryAddBlock::create([
	                'line_user_id' => $lineUserProfile->id,
	                'action' => 'follow'
	            ]);
	            $replyToken = $body['events'][0]['replyToken'];
	            $lineUserProfile->update([ 'is_follow' => 1 ]);
	            // GreetingMessage::sendMessageGreeting($mid,$lineUserProfile,$replyToken);
	        }

	        if ( isset($header['x-line-signature']) ) {
	        	$xsignature = $header['x-line-signature'][0];
	        	// ForwardDataRecieve::forewardData($body,$xsignature);

	            if($type == 'postback'){
	              	$event = "postback";
	            }else{
	              	$event = LineWebHooks::getMessageEvent($body);
	            }
	            //problem with  Thai lang
	            $pass = LineWebHooks::checkSignature($xsignature, $body);

	            if($event == 'location'){
	                Log::debug('in case location');
	                LineWebHooks::shareLocation($body, $dateNowStart);
	                // Log::debug($body);
	            }else{
	                if($type == 'message'){
	                	$messageTypeText = $body['events'][0]['message']['type'];
	                    if($messageTypeText == 'text'){
	                        $text = $body['events'][0]['message']['text'];
	                        if($text == 'เช็คCustomer'){
	                        	EplusCustomerCoreLineFucntion::sentMessage($body, $dateNowStart);
	                        }else if($text == 'เช็คSalesman'){
	                        	EplusSalesmanCoreLineFunction::sentMessage($body, $dateNowStart);
	                        }else{
	                        	$checkURL = CoreFunction::checkURL($text);
		                        if($checkURL){
		                            $event = 'url';
		                            LineWebHooks::sentMessageDefaultURL($body, $dateNowStart);
		                        }
	                        }
	                    }
	                }
	            }
	            Log::debug('type => '.$event);

	            //Log::debug(' status =>'. $pass);
	            //if ($pass || $event=='location') {
	            if ($pass) {
	                Log::debug('event => '. $event);
	                switch (trim($event)) {
	                    case 'message':
	                        Log::debug('in case message');
	                        //LineWebHooks::developing($body);
	                        LineWebHooks::autoReplyMessage($body, $dateNowStart);
	                        break;
	                    case 'image':
	                        LineWebHooks::developing($body);
	                        
	                        break;
	                    case 'video':
	                       LineWebHooks::developing($body);
	                        
	                        break;
	                    case 'audio':
	                        LineWebHooks::developing($body);
	                    
	                        break;
	                    case 'location':
	                        
	                        break;
	                    case 'sticker':
	                        Log::debug('in case Sticker');
	                        //LineWebHooks::developing($body);
	                        LineWebHooks::defalutSticker($body);
	                        break;
	                     case 'follow':
	                        
	                        break;
	                     case 'join':
	                            LineWebHooks::eventJoin($body);
	                        break;
	                     case 'unfollow':
	                            LineWebHooks::eventLeave($body);
	                        break;
	                    case 'postback':
	                    	$postbackData = $body['events'][0]['postback']['data'];
                        	$explodeData = explode('|', $postbackData);
                        	if(count($explodeData) > 0 && $explodeData[0] == 'template'){
                        		TemplateMessageCoreFunction::sendMessagePostBack($body, $explodeData[1], $dateNowStart,$lineUserProfile);
                        	}
	                    		// if($checkApproval == 1){
		                     //        LineWebHooks::postBackData($body, $dateNowStart);
		                     //    }
	                        break;
	                    default:
	                        Log::debug('default Message Event Not Match');
	                }
	            }
	        }

	        Log::debug('End Post Receive');
	        $now = Carbon::now();
	        $dateNow2 = $now;
	        Log::debug('---------');
	    }
	    catch(Exception $e) {
            Log::debug('Error '.$e);
        }

        return 1;
    }
}
