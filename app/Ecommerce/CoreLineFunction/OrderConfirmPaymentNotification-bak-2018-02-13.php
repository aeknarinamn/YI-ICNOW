<?php

namespace YellowProject\Ecommerce\CoreLineFunction;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\CoreLineFunction\LineFunction;
use YellowProject\LineWebHooks;
use YellowProject\LineSettingBusiness;
use YellowProject\Ecommerce\DTManagement\DTManagementRegisterData;
use YellowProject\Ecommerce\DTManagement\DTManagement;

class OrderConfirmPaymentNotification extends Model
{
	public static function pushNotificationCustomer($order,$orderPayment)
    {
    	$customer = $order->customer;
    	$lineUserProfile = $customer->lineUserProfile;

    	$text = "แจ้งยืนยันการชำระเงิน ##newline## ผู้สั่งซื้อ : ([firstName]) ##newline## เลขที่การสั่งซื้อ : ([orderId]) ##newline## ยอดเงินที่ชำระ(รวมภาษีแล้ว) : ([amount]) ##newline## ชำระด้วยวิธี : ([paymentMethod])  ##newline##  รหัสอ้างอิงการชำระเงิน : ([paymentNumber])";
    	$messages = collect([]);
    	$data = [
            "type" => "text",
            "text" => self::sentPayload($text,$order,$customer,$orderPayment)
        ];
        $messages->push($data);
        LineFunction::pushMessage($lineUserProfile,$messages);
        self::pushNotificationDT($order,$orderPayment);

        return 1;
    }

    public static function pushNotificationDT($order,$orderPayment)
    {
        $customer = $order->customer;
        $lineUserProfile = $customer->lineUserProfile;
        $dtID = $customer->dt_id;
        $DTManagement = DTManagement::find($dtID);
        $DTManagementRegisterDatas = DTManagementRegisterData::where('dt_code',$DTManagement->dt_code_login)->get();
        foreach ($DTManagementRegisterDatas as $key => $DTManagementRegisterData) {
            $lineUserProfile = $DTManagementRegisterData->lineUserProfile;
            $datas = collect();
            $text = "มีผู้แจ้งการชำระเงิน ##newline## ผู้สั่งซื้อ : ([firstName]) ##newline## เลขที่การสั่งซื้อ : ([orderId]) ##newline## ยอดเงินที่ชำระ(รวมภาษีแล้ว) : ([amount]) ##newline## ชำระด้วยวิธี : ([paymentMethod])  ##newline##  รหัสอ้างอิงการชำระเงิน : ([paymentNumber]) กรุณา Confirm การจัดส่งสินค้า ##newline## Click --> ".$DTManagement->dt_url_login;
            $messages = collect([]);
            $data = [
                "type" => "text",
                "text" => self::sentPayload($text,$order,$customer,$orderPayment)
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
        }

        return 1;
    }

    public static function sentPayload($payload,$order,$customer,$orderPayment)
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
                    if($match[0] == 'orderId'){
                        $payloads[$key] = $order->order_id;
                    }
                    if($match[0] == 'amount'){
                        $payloads[$key] = number_format($orderPayment->payment_amount,2);
                    }
                    if($match[0] == 'firstName' || $match[0] == 'firstname'){
                        $payloads[$key] = $customer->first_name." ".$customer->last_name;
                    }
                    if($match[0] == 'paymentMethod'){
                        $payloads[$key] = $orderPayment->payment_type;
                    }
                    if($match[0] == 'paymentNumber'){
                        $payloads[$key] = $orderPayment->payment_transaction_id;
                    }
                    $payloads[$key] = trim($payloads[$key]);
                }
            }
        }
        // dd($payloads);
        $keyword = implode(" ", $payloads);

        $keyword = preg_replace("/<span[^>]+\>/i", "", $keyword);
        // $keyword = str_replace('\n', PHP_EOL, $keyword);
        $keyword = str_replace(' ##newline## ', PHP_EOL, $keyword);
        return $keyword;
    }
}
