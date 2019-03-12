<?php

namespace YellowProject\Ecommerce\CoreLineFunction;

use Illuminate\Database\Eloquent\Model;
use YellowProject\LineWebHooks;
use YellowProject\LineSettingBusiness;
use YellowProject\Ecommerce\DTManagement\DTManagementRegisterData;
use YellowProject\Ecommerce\DTManagement\DTManagement;
use YellowProject\Ecommerce\LineTemplateMessage\OrderEnd\LineOrderEnd;
use YellowProject\Ecommerce\LineTemplateMessage\LineTemplateMessageAuto\OrderEnd;
use YellowProject\Ecommerce\AdminLineUser;

class LineFunction extends Model
{
    // public static function sentMessageCustomerEndOrder($customer,$order)
    // {
    //     $lineUserProfile = $customer->lineUserProfile;
    //     $datas = collect();
    //     $messages = collect([]);
    //     $text = "ขอบพระคุณสำหรับการสั่งซื้อสินค้าค่ะ ##newline## ผู้สั่งซื้อ : ([firstName]) ##newline## เลขที่การสั่งซื้อ : ([orderId]) ##newline## รายละเอียดการสั่งซื้อสินค้า ##newline## ([products]) ##newline## ยอดเงินที่ต้องชำระ(รวมภาษีแล้ว) : ([amount])";
    //     $data = [
    //         "type" => "text",
    //         "text" => self::sentPayload($text,$order,$customer)
    //     ];
    //     $messages->push($data);
    //     $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();
    //     $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/push');
    //     $datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);
    //     $data = collect([
    //         "to" => $lineUserProfile->mid,
    //         "messages"   => $messages,
    //     ]);
    //     $datas->put('data', $data->toJson());
    //     $sent = LineWebHooks::sent($datas);

    //     return 1;
    // }

    // public static function sentMessageDTEndOrder($customer,$order)
    // {
    //     $dtID = $customer->dt_id;
    //     $DTManagement = DTManagement::find($dtID);
    //     $DTManagementRegisterDatas = DTManagementRegisterData::where('dt_code',$DTManagement->dt_code_login)->get();
    //     foreach ($DTManagementRegisterDatas as $key => $DTManagementRegisterData) {
    //         $lineUserProfile = $DTManagementRegisterData->lineUserProfile;
    //         $datas = collect();
    //         $text = "มีผู้สั่งซื้อสินค้า ##newline## ผู้สั่งซื้อ : ([firstName]) ##newline## เลขที่การสั่งซื้อ : ([orderId]) ##newline## รายละเอียดการสั่งซื้อสินค้า ##newline## ([products]) ##newline## ยอดเงินที่ต้องชำระ(รวมภาษีแล้ว) : ([amount]) ##newline## กรุณา Confirm Order ##newline## Click --> ".$DTManagement->dt_url_login;
    //         $messages = collect([]);
    //         $data = [
    //             "type" => "text",
    //             "text" => self::sentPayload($text,$order,$customer)
    //         ];
    //         $messages->push($data);
    //         $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();
    //         $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/push');
    //         $datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);
    //         $data = collect([
    //             "to" => $lineUserProfile->mid,
    //             "messages"   => $messages,
    //         ]);
    //         $datas->put('data', $data->toJson());
    //         $sent = LineWebHooks::sent($datas);
    //     }

    //     return 1;
    // }

    // public static function sentMessageCustomerShipping($customer,$order)
    // {
    //     $lineUserProfile = $customer->lineUserProfile;
    //     $datas = collect();
    //     $text = "ขอบพระคุณสำหรับการสั่งซื้อสินค้าค่ะ ##newline## ผู้สั่งซื้อ : ([firstName]) ##newline## เลขที่การสั่งซื้อ : ([orderId]) ##newline## ยอดเงินที่ต้องชำระ(รวมภาษีแล้ว) : ([amount])";
    //     $messages = collect([]);
    //     $data = [
    //         "type" => "text",
    //         "text" => self::sentPayload($text,$order,$customer)
    //     ];
    //     $messages->push($data);
    //     $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();
    //     $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/push');
    //     $datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);
    //     $data = collect([
    //         "to" => $lineUserProfile->mid,
    //         "messages"   => $messages,
    //     ]);
    //     $datas->put('data', $data->toJson());
    //     $sent = LineWebHooks::sent($datas);

    //     return 1;
    // }

    public static function sentMessageCustomerFirstRegister($customer)
    {
        $lineUserProfile = $customer->lineUserProfile;
        $datas = collect();
        $text = "ขอบพระคุณสำหรับการสมัครสมาชิกค่ะ คุณ ([firstName]) สามารถใช้คูปองส่วนลด 500 บาท เมื่อทำการซื้อสินค้าครบ 2500 บาท ได้อีก 3 ครั้ง";
        $messages = collect([]);
        $data = [
            "type" => "text",
            "text" => self::sentPayload($text,$order = null,$customer)
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

    public static function sentMessageCustomerEndOrder($customer,$order)
    {
        $lineUserProfile = $customer->lineUserProfile;

        $messages = self::setSendMessageToCustomer($customer,$order);
        $message = collect($messages);
        if($customer->dt_id != -1){
            self::pushMessage($lineUserProfile,$message);
            self::autoPushNotificationDT($customer,$order);
            self::autoPushNotificationAdmin($customer,$order);
        }

        return 1;
    }

    public static function autoPushNotificationAdmin($customer,$order)
    {
        $customer = $order->customer;
        $adminLineUsers = AdminLineUser::where('is_user',1)->get();
        foreach ($adminLineUsers as $key => $adminLineUser) {
            $lineUserProfile = $adminLineUser->lineUserProfile;
            $messages = self::setSendMessageToAdmin($customer,$order,$lineUserProfile);
            $message = collect($messages);
            self::pushMessage($lineUserProfile,$message);
        }

        return 1;
    }

    public static function autoPushNotificationDT($customer,$order)
    {
        $customer = $order->customer;
        $dtID = $order->dt_id;
        $DTManagement = DTManagement::find($dtID);
        $DTManagementRegisterDatas = DTManagementRegisterData::where('dt_code',$DTManagement->dt_code_login)->get();
        foreach ($DTManagementRegisterDatas as $key => $DTManagementRegisterData) {
            $lineUserProfile = $DTManagementRegisterData->lineUserProfile;
            $messages = self::setSendMessageToDT($customer,$order,$lineUserProfile,$DTManagement);
            $message = collect($messages);
            self::pushMessage($lineUserProfile,$message);
        }

        return 1;
    }

    public static function setSendMessageToAdmin($customer,$order,$lineUserProfile)
    {
        $dtID = $order->dt_id;
        $DTManagement = DTManagement::find($dtID);
        $orderConfirmation = OrderEnd::first();
        $lineOrderConfirmation = LineOrderEnd::where('id',$orderConfirmation->line_to_admin_template_id)->first();
        $setData = self::setDataConfirmation($customer,$order,$lineOrderConfirmation,$DTManagement);

        return $setData;
    }

    public static function setSendMessageToDT($customer,$order,$lineUserProfile,$DTManagement)
    {
        $dtID = $order->dt_id;
        $DTManagement = DTManagement::find($dtID);
        $orderConfirmation = OrderEnd::first();
        $lineOrderConfirmation = LineOrderEnd::where('id',$orderConfirmation->line_to_dt_template_id)->first();
        $setData = self::setDataConfirmation($customer,$order,$lineOrderConfirmation,$DTManagement);

        return $setData;
    }

    public static function setSendMessageToCustomer($customer,$order)
    {
        $dtID = $order->dt_id;
        $DTManagement = DTManagement::find($dtID);
        $orderConfirmation = OrderEnd::first();
        $lineOrderConfirmation = LineOrderEnd::where('id',$orderConfirmation->line_to_customer_template_id)->first();
        $setData = self::setDataConfirmation($customer,$order,$lineOrderConfirmation,$DTManagement);

        return $setData;
    }

    public static function setDataConfirmation($customer,$order,$lineOrderConfirmation,$DTManagement = null)
    {
        // $lineConfirmation = $order->orderConfirmation;
        // $lineOrderConfirmation = LineOrderConfirmation::where('id',$lineConfirmation->line_to_customer_template_id)->first();
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
                            "text" => self::sentPayload($item->message->message,$order,$customer,$DTManagement)
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

    public static function sentPayload($payload,$order,$customer,$DTManagement = null)
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

    public static function pushMessage($lineUserProfile,$messages)
    {
        $datas = collect();
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

}
