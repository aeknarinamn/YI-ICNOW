<?php

namespace YellowProject\Ecommerce\Task;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Task\SetTask;
use YellowProject\Ecommerce\DTManagement\DTManagement;
use YellowProject\Ecommerce\Customer\Customer;
use YellowProject\LineWebHooks;
use YellowProject\LineSettingBusiness;

class CoreFunction extends Model
{
    public static function checkDtTask()
    {
    	$tasks = SetTask::where('type','dt_send_message')->where('is_active',1)->get();
    	foreach ($tasks as $key => $task) {
    		$task->update([
    			'is_active' => 0
    		]);
    		$DTManagement = DTManagement::find($task->main_id);
    		$postCodes = $DTManagement->postCodes;
    		foreach ($postCodes as $key => $postCode) {
    			$customers = Customer::where('post_code',$postCode->post_code)->where('dt_id',-1)->get();
    			foreach ($customers as $key => $customer) {
    				$customer->update([
    					'dt_id' => $DTManagement->id
    				]);
    				self::sentMessageCustomer($customer,$DTManagement);
    			}
    		}
    	}
    }

    public static function sentMessageCustomer($customer,$DTManagement)
    {
        $lineUserProfile = $customer->lineUserProfile;
        $datas = collect();
        $messages = collect([]);
        $text = "DT ในจังหวัดที่ท่านเลือกพร้อมให้บริการแล้ว";
        $data = [
            "type" => "text",
            "text" => self::sentPayload($text,$customer,$DTManagement)
        ];
        $messages->push($data);
        $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();
        $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/push');
        $datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);
        $data = collect([
            "to" => $lineUserProfile->mid,
            "messages"   => $messages,
        ]);
        $datas->put('data', $data->toJson());
        $sent = LineWebHooks::sent($datas);

        return 1;
    }

    public static function sentPayload($payload,$customer,$DTManagement = null)
    {
        $string='';
        $subscriberID = '';
        $keyword = '';
        $newPayloads = $payload;

        $newPayloads = str_replace(trim('&nbsp;'), ' ', trim($newPayloads));
        $newPayloads = str_replace(trim(' '), ' ', trim($newPayloads));
        $newPayloads = preg_replace('#(www\.|https?:\/\/){1}[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}(\S*)#i', ' $0', $newPayloads);
        $keywords = preg_split("/\s+/", $newPayloads);
        foreach ($keywords as $key => $messageText) {
            $string .= " ".$messageText;
        }
        $keyword = $string;
        // dd($keyword);
        $valueForQuery = collect();
        $regStrings = preg_split("/[@##][@###]+/",$string);
        foreach ($regStrings as $regString) {
          if(trim($regString) !=''){
                $first = substr($regString, 0, 2);
                if($first == '{[') {
                    $last = substr($regString,-2);
                    if($last == ']}'){
                        $data = substr($regString,2,strlen($regString)-4);
                        $valueForQuery->push($data);
                    }
                }
            }
        }
        foreach($valueForQuery as $value){
            $data = str_replace(".png", "", $value);
            $lineEmoticon = LineEmoticon::where('file_name',$data)->first();
            // dd($lineEmoticon->sent_unicode);
            if(!is_null($lineEmoticon)){
                $keyword = str_replace('&nbsp;', ' ', trim($keyword));
                $keyword = str_replace('@##'.trim('{['.$value.']}@###'), ' '.$lineEmoticon->sent_unicode, trim($keyword));
            }
        }
        $keyword = preg_replace_callback("~\(([^\)]*)\)~", function($s) {
            return str_replace(" ", "%S", "($s[1])");
        }, $keyword);
        $payloads = explode(" ", $keyword);

        foreach ($payloads as $key => $value) {
            if($payloads[$key] != ""){
                // preg_match('#\<(.*?)\>#', $payloads[$key], $match);
                $payloads[$key] = str_replace("%S", " ", $payloads[$key]);
                preg_match('#\(\[.*?\]\)#', $payloads[$key], $match);
                if(count($match) > 0){
                    $keyword = str_replace('([', '', $match[0]);
                    $keyword = str_replace('])', '', $keyword);
                    $match[0] = trim($keyword);
                    if($match[0] == 'firstName' || $match[0] == 'firstname'){
                        $payloads[$key] = $customer->first_name." ".$customer->last_name;
                    }
                    if($match[0] == 'First_name'){
                        $payloads[$key] = $customer->first_name;
                    }
                    if($match[0] == 'Last_name'){
                        $payloads[$key] = $customer->last_name;
                    }
                    if($match[0] == 'Phone_number'){
                        $payloads[$key] = $customer->phone_number;
                    }
                    if($match[0] == 'Dt_Name'){
                        $payloads[$key] = $DTManagement->name;
                    }
                    if($match[0] == 'Line_at_link'){
                        $payloads[$key] = $DTManagement->line_at_link;
                    }
                    $payloads[$key] = trim($payloads[$key]);
                }
            }
        }
        // dd($payloads);
        $keyword = implode("", $payloads);

        $keyword = preg_replace("/<span[^>]+\>/i", "", $keyword);
        // $keyword = str_replace('\n', PHP_EOL, $keyword);
        $keyword = str_replace(' ##newline## ', PHP_EOL, $keyword);
        $keyword = str_replace('##newline##', PHP_EOL, $keyword);
        return trim($keyword);
    }
}
