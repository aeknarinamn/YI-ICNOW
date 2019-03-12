<?php

namespace YellowProject\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use YellowProject\LineWebHooks;
use AWS;

class CoreFunction extends Model
{
    public static function sendLineMessageCustomer($lineUserProfile)
    {
    	$messages = array();
        $messages[]  = [
            "type" => "text",
            "text" => "ขอบคุณที่ซื้อสินค้า"
        ];

        self::coreSendMessageLine($messages,$lineUserProfile);
    }

    public static function sendLineMessageAdmin()
    {
    	$messages = array();
        $messages[]  = [
            "type" => "text",
            "text" => "ขอบคุณที่ซื้อสินค้า"
        ];

        self::coreSendMessageLine($messages,$lineUserProfile);
    }

    public static function sendLineMessageDT()
    {
    	$messages = array();
        $messages[]  = [
            "type" => "text",
            "text" => "ขอบคุณที่ซื้อสินค้า"
        ];

        self::coreSendMessageLine($messages,$lineUserProfile);
    }

    public static function sendEmailCustomer($customer)
    {
    	
    }

    public static function sendEmailAdmin()
    {
    	
    }

    public static function sendEmailDT()
    {
    	
    }

    public static function coreSendMessageLine($messages,$lineUserProfile)
    {
    	$lineSettingBusiness = LineSettingBusiness::where('active',true)->first();
        $datas = collect();
        $datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);

        $message = collect($messages);
            
        $data = collect([
            "to" => $lineUserProfile->mid,
            "messages"   => $message
        ]);

        $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/push');
        $datas->put('data', $data->toJson());
        LineWebHooks::sent($datas);
    }

    public static function sendSms()
    {
        require base_path() . '/vendor/autoload.php';

        $params = array(
            'credentials' => array(
                'key' => 'AKIAIFBA2O3DQGXKSEQQ',
                'secret' => 'ndLtkrZHReDpnh3Z4WTMNaUknUBhbzCC9LZef8Gn',
            ),
            'region' => 'us-east-1', // < your aws from SNS Topic region
            'version' => 'latest'
        );
        $sns = new \Aws\Sns\SnsClient($params);

        $args = array(
            "MessageAttributes" => [
                        'AWS.SNS.SMS.SenderID' => [
                            'DataType' => 'String',
                            'StringValue' => 'YOUR_SENDER_ID'
                        ],
                        'AWS.SNS.SMS.SMSType' => [
                            'DataType' => 'String',
                            'StringValue' => 'Transactional'
                        ]
                    ],
            "Message" => "Hello World! Visit www.tiagogouvea.com.br!",
            "PhoneNumber" => "FULL_PHONE_NUMBER"
        );

        $result = $sns->publish($args);
    }
}
