<?php

namespace YellowProject\Ecommerce\CoreLineFunction;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\CoreLineFunction\LineFunction;
use YellowProject\LineWebHooks;
use YellowProject\LineSettingBusiness;
use YellowProject\Ecommerce\LineTemplateMessage\ShippingConfirmation\LineShippingConfirmation;
use YellowProject\Ecommerce\LineTemplateMessage\LineTemplateMessageAuto\ShippingConfirm;
use YellowProject\RichMessageMain;
use YellowProject\Ecommerce\DTManagement\DTManagementRegisterData;
use YellowProject\Ecommerce\DTManagement\DTManagement;
use YellowProject\Ecommerce\AdminLineUser;

class OrderConfirmShippingNotification extends Model
{
    public static function pushNotificationShippingCustomer($order,$customer,$orderPayment,$orderConfirmation)
    {
    	// $customer = $order->customer;
    	$lineUserProfile = $customer->lineUserProfile;

    	// $text = "แจ้งยืนยันการส่งสินค้า ##newline## ผู้สั่งซื้อ : ([firstName]) ##newline## เลขที่การสั่งซื้อ : ([orderId]) ##newline## รหัสอ้างอิงการชำระเงิน : ([paymentNumber])  ##newline## ขณะนี้กำลังดำเนินการส่งสินค้าค่ะ";
    	// $messages = collect([]);
    	// $data = [
     //        "type" => "text",
     //        "text" => self::sentPayload($text,$order,$customer,$orderPayment)
     //    ];
     //    $messages->push($data);
     //    LineFunction::pushMessage($lineUserProfile,$messages);

        $messages = self::setSendMessageToCustomer($customer,$order,$orderPayment,$lineUserProfile);
        $message = collect($messages);
        LineFunction::pushMessage($lineUserProfile,$message);
        self::autoPushNotificationDT($customer,$order,$orderPayment);
        self::autoPushNotificationAdmin($customer,$order,$orderPayment);

        return 1;
    }

    public static function autoPushNotificationAdmin($customer,$order,$orderPayment)
    {
        $customer = $order->customer;
        $adminLineUsers = AdminLineUser::where('is_user',1)->get();
        foreach ($adminLineUsers as $key => $adminLineUser) {
            $lineUserProfile = $adminLineUser->lineUserProfile;
            $messages = self::setSendMessageToAdmin($customer,$order,$orderPayment,$lineUserProfile);
            $message = collect($messages);
            LineFunction::pushMessage($lineUserProfile,$message);
        }

        return 1;
    }

    public static function autoPushNotificationDT($customer,$order,$orderPayment)
    {
        $customer = $order->customer;
        $dtID = $order->dt_id;
        $DTManagement = DTManagement::find($dtID);
        $DTManagementRegisterDatas = DTManagementRegisterData::where('dt_code',$DTManagement->dt_code_login)->get();
        foreach ($DTManagementRegisterDatas as $key => $DTManagementRegisterData) {
            $lineUserProfile = $DTManagementRegisterData->lineUserProfile;
            $messages = self::setSendMessageToDT($customer,$order,$orderPayment,$lineUserProfile);
            $message = collect($messages);
            LineFunction::pushMessage($lineUserProfile,$message);
        }

        return 1;
    }

    public static function setSendMessageToAdmin($customer,$order,$orderPayment,$lineUserProfile)
    {
        $dtID = $customer->dt_id;
        $DTManagement = DTManagement::find($dtID);
        $orderConfirmation = ShippingConfirm::first();
        $lineOrderConfirmation = LineShippingConfirmation::where('id',$orderConfirmation->line_to_admin_template_id)->first();
        $setData = self::setDataConfirmation($customer,$order,$orderPayment,$lineUserProfile,$lineOrderConfirmation,$DTManagement);

        return $setData;
    }

    public static function setSendMessageToDT($customer,$order,$orderPayment,$lineUserProfile)
    {
        $dtID = $customer->dt_id;
        $DTManagement = DTManagement::find($dtID);
        $orderConfirmation = ShippingConfirm::first();
        $lineOrderConfirmation = LineShippingConfirmation::where('id',$orderConfirmation->line_to_dt_template_id)->first();
        $setData = self::setDataConfirmation($customer,$order,$orderPayment,$lineUserProfile,$lineOrderConfirmation,$DTManagement);

        return $setData;
    }

    public static function setSendMessageToCustomer($customer,$order,$orderPayment,$lineUserProfile)
    {
        // $lineConfirmation = $order->orderShippingConfirmation;
        // $lineOrderConfirmation = LineShippingConfirmation::where('id',$lineConfirmation->line_to_customer_template_id)->first();
        // $setData = self::setDataConfirmation($customer,$order,$orderPayment,$lineUserProfile,$lineOrderConfirmation);

        // return $setData;

        $dtID = $customer->dt_id;
        $DTManagement = DTManagement::find($dtID);
        $orderConfirmation = ShippingConfirm::first();
        $lineOrderConfirmation = LineShippingConfirmation::where('id',$orderConfirmation->line_to_customer_template_id)->first();
        $setData = self::setDataConfirmation($customer,$order,$orderPayment,$lineUserProfile,$lineOrderConfirmation,$DTManagement);

        return $setData;
    }

    public static function setDataConfirmation($customer,$order,$orderPayment,$lineUserProfile,$lineOrderConfirmation,$DTManagement)
    {
        // $lineConfirmation = $order->orderShippingConfirmation;
        // $lineOrderConfirmation = LineShippingConfirmation::where('id',$lineConfirmation->line_to_customer_template_id)->first();
        // \Log::debug($lineConfirmation->line_to_customer_template_id);
        $messages = array();
        if($lineOrderConfirmation){

            $items  = $lineOrderConfirmation->lineItems;
            if (sizeof($items) > 0) {
                foreach ($items as $key => $item) {
                    if ($item->messageType->type == 'text') {
                        \Log::debug($item->message->message);
                        $messages[$key]  = [
                            "type" =>"text",
                            "text" => self::sentPayload($item->message->message,$lineUserProfile,$order,$customer,$orderPayment,$DTManagement)
                        ];
                    } elseif ($item->messageType->type == 'sticker') {
                        $messages[$key]  = [
                            "type" =>"sticker",
                            "packageId" => ''.$item->sticker->packageId.'',
                            "stickerId" => ''.$item->sticker->stickerId.'',
                        ];
                    } elseif ($item->messageType->type == 'imagemap'){
                        $richMessageMain = RichMessageMain::find($item->richmessage_id);
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

    public static function sentPayload($payload,$lineUserProfile,$order,$customer,$orderPayment,$DTManagement)
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
                        $payloads[$key] = number_format($order->total_paid,2);
                    }
                    if($match[0] == 'firstName' || $match[0] == 'firstname'){
                        $payloads[$key] = $customer->first_name." ".$customer->last_name;
                    }
                    if($match[0] == 'linkPayment'){
                        $payloads[$key] = $paymentURL;
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
                    if($match[0] == 'Total_summary'){
                        $payloads[$key] = number_format($order->total_paid,2);
                    }
                    if($match[0] == 'Discount_price'){
                        $payloads[$key] = number_format($order->coupon_code_discount_amount,2);
                    }
                    if($match[0] == 'products'){
                        $payloads[$key] = self::genProduct($order);
                    }
                    if($match[0] == 'Dt_Name'){
                        $payloads[$key] = $DTManagement->name;
                    }
                    if($match[0] == 'Dt_Tel'){
                        $payloads[$key] = $DTManagement->tel;
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
        $keyword = str_replace('  ##newline##  ', PHP_EOL, $keyword);
        $keyword = str_replace(' ##newline## ', PHP_EOL, $keyword);
        $keyword = str_replace('##newline## ', PHP_EOL, $keyword);
        $keyword = str_replace('##newline##', PHP_EOL, $keyword);
        return ltrim($keyword);
    }

    public static function genProduct($order)
    {
        $payloadProducts = "";
        $orderProducts = $order->orderProducts;
        foreach ($orderProducts as $key => $orderProduct) {
            $payloadProducts .= $orderProduct->product->name." : ".$orderProduct->quanlity." ".$orderProduct->product->price_unit." = ".$orderProduct->total." บาท ".PHP_EOL;
        }

        return $payloadProducts;
    }
}
