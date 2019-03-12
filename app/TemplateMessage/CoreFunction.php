<?php

namespace YellowProject\TemplateMessage;

use Illuminate\Database\Eloquent\Model;
use YellowProject\TemplateMessage\TemplateMessage;
use YellowProject\AutoReplyKeyword;
use YellowProject\LineWebHooks;
use YellowProject\AutoReplyKeywordMessage;
use YellowProject\LineSettingBusiness;
use Carbon\Carbon;

class CoreFunction extends Model
{
    public static function setTemplateMessage($templateMessageId)
    {
    	$templateMessage = TemplateMessage::find($templateMessageId);
    	$templateMessageColumns = $templateMessage->templateMessageColumns;
    	$messages = [];
    	$messages = [
            "type" => "template",
            "altText" => $templateMessage->alt_text,
        ];
        if($templateMessage->type == 'confirm'){
        	$templateMessageColumn = $templateMessageColumns->first();
        	$templateMessageActions = $templateMessageColumn->templateMessageActions;
        	$messages['template']['type'] = 'confirm';
        	$messages['template']['text'] = $templateMessageColumn->title;
        	foreach ($templateMessageActions as $key => $templateMessageAction) {
        		$dataItems[] = self::setTemplateMessageAction($templateMessageAction);
	        }
	        $messages['template']['actions'] = $dataItems;
        }else if($templateMessage->type == 'buttons'){
        	$templateMessageColumn = $templateMessageColumns->first();
        	$templateMessageActions = $templateMessageColumn->templateMessageActions;
        	$messages['template']['type'] = 'buttons';
        	$messages['template']['thumbnailImageUrl'] = $templateMessageColumn->img_url;
        	$messages['template']['imageAspectRatio'] = 'rectangle';
        	$messages['template']['imageSize'] = 'cover';
        	// $messages['template']['imageBackgroundColor'] = '';
        	// $messages['template']['title'] = '';
        	$messages['template']['text'] = $templateMessageColumn->title;
        	foreach ($templateMessageActions as $key => $templateMessageAction) {
        		$dataItems[] = self::setTemplateMessageAction($templateMessageAction);
	        }
	        $messages['template']['actions'] = $dataItems;
        }else if($templateMessage->type == 'carousel'){
        	$messages['template']['type'] = 'carousel';
        	$templateMessageColumns = $templateMessageColumns;
        	foreach ($templateMessageColumns as $key => $templateMessageColumn) {
        		$messages['template']['columns'][$key]['thumbnailImageUrl'] = $templateMessageColumn->img_url;
        		$messages['template']['columns'][$key]['title'] = $templateMessageColumn->title;
        		$messages['template']['columns'][$key]['text'] = $templateMessageColumn->desc;
        		$templateMessageActions = $templateMessageColumn->templateMessageActions;
        		$dataItems = [];
        		foreach ($templateMessageActions as $index => $templateMessageAction) {
	        		$dataItems[] = self::setTemplateMessageAction($templateMessageAction);
		        }
	        	$messages['template']['columns'][$key]['actions'] = $dataItems;
        	}
        }else if($templateMessage->type == 'image_carousel'){
        	$messages['template']['type'] = 'image_carousel';
        	$templateMessageColumns = $templateMessageColumns;
        	foreach ($templateMessageColumns as $key => $templateMessageColumn) {
        		$messages['template']['columns'][$key]['imageUrl'] = $templateMessageColumn->img_url;
        		$templateMessageActions = $templateMessageColumn->templateMessageActions;
        		$templateMessageAction = $templateMessageActions->first();
        		$actions = self::setTemplateMessageAction($templateMessageAction);
        		// dd($actions);
        		$messages['template']['columns'][$key]['action'] = $actions;
        	}
        }
        // dd($messages);

        return $messages;
    }

    public static function setTemplateMessageAction($templateMessageAction)
    {
    	$datas = [];
    	if($templateMessageAction->action == 'Message'){
    		$datas = [
    			'type' => 'message',
    			'label' => $templateMessageAction->label,
    			'text' => $templateMessageAction->label
    		];
    	}
    	else if($templateMessageAction->action == 'Link URL'){
    		$datas = [
    			'type' => 'uri',
    			'label' => $templateMessageAction->label,
    			'uri' => $templateMessageAction->value
    		];
    	}else if($templateMessageAction->action == 'Tel'){
    		$datas = [
    			'type' => 'uri',
    			'label' => $templateMessageAction->label,
    			'uri' => 'tel:'.$templateMessageAction->value
    		];
    	}else if($templateMessageAction->action == 'Post Back'){
            $datas = [
                'type' => 'postback',
                'label' => $templateMessageAction->label,
                'data' => 'template|'.$templateMessageAction->value
            ];
        }

    	return $datas;
    }

    public static function sendMessagePostBack($body,$postBack,$dateStartNow = null,$lineUserProfile)
    {
        $autoReplyKeyWord  = AutoReplyKeyword::where('title',$postBack)->first();
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
                            // $messages = array();
                            $messages[] = LineWebHooks::setImagemap($item->auto_reply_richmessage_id);
                            // Log::debug($messages);
                        } elseif ($item->messageType->type == 'image') {
                            $messages[]  = [
                                "type" =>"image",
                                "originalContentUrl" => $item->original_content_url,
                                "previewImageUrl" => $item->preview_image_url,
                            ];                                       
                        } elseif ($item->messageType->type == 'video') {
                            $messages[]  = [
                                "type" =>"video",
                                "originalContentUrl" => $item->original_content_url,
                                "previewImageUrl" => $item->preview_image_url,
                            ];                                       
                        } elseif ($item->messageType->type == 'template_message') {
                            $messages[] = self::setTemplateMessage($item->template_message_id);                               
                        }
                    }
                }                              
            }                     
        }

        $datas = collect();

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
                $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/push');
                if ($datas['sourceType'] == 'user') {
                    $data = collect([
                        "to" => $datas['userId'],
                        "replyToken" => $datas['replyToken'], // for test
                        "messages"   => $message
                    ]); 
                }
            } else {
                $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/reply');
                $data = collect([
                    "replyToken" => $body['events'][0]['replyToken'],
                    "messages"   => $message
                ]); 
            }

            $datas->put('data', $data->toJson());
            
            LineWebHooks::sent($datas);                
        }

    }
}
