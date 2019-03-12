<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\LineWebHooks;
use YellowProject\LineSettingBusiness;
use YellowProject\DownloadFile\DownloadFileMain;
use YellowProject\DownloadFile\DownloadFileMainFunction;
use YellowProject\ICNOW\Mini\MiniUser;
use YellowProject\ICNOW\APIConnection\MiniConnection;
use YellowProject\ICNOW\OrderCustomer\OrderCustomer;
use YellowProject\ICNOW\CoreLineFunction\CoreLineFunction;
use Carbon\Carbon;

class JobScheduleFunction extends Model
{
    public static function checkFunctionDownload()
    {
        $downloadFileMains = DownloadFileMain::where('is_active',1)->get();
        foreach ($downloadFileMains as $key => $downloadFileMain) {
            if($downloadFileMain->type == 'subscriber_single'){
                $downloadFileMain->update([
                    'is_active' => 0
                ]);
                DownloadFileMainFunction::downloadFileSubscriberSingle($downloadFileMain->main_id);
            }else if($downloadFileMain->type == 'keyword_inquiry'){
                $downloadFileMain->update([
                    'is_active' => 0
                ]);
                DownloadFileMainFunction::downloadFileKeywordInquiry(unserialize($downloadFileMain->requests));
            }
        }
    }

    public static function refreshToken()
    {
        $refreshToken = LineWebHooks::refreshToken();
        $lineSettingBusiness = LineSettingBusiness::where('active',1)->first();
        $lineSettingBusiness->update([
            'channel_access_token' => $refreshToken->access_token,
        ]);
    }

    public static function getMiniUser()
    {
        $responses = MiniConnection::getUserMini();
        if(count($responses) > 0){
            $datas = $responses;
            foreach ($datas as $key => $data) {
                $miniUser = MiniUser::where('username',$data->username)->first();
                if($miniUser){
                    $miniUser->update([
                        'username' => $data->username,
                        'password' => $data->password,
                        'mini_code' => $data->mini_code,
                        'dt_code' => $data->dt_code,
                        'mini_name' => $data->mini_name,
                        'dt_name' => $data->dt_name,
                    ]);
                }else{
                    MiniUser::create([
                        'username' => $data->username,
                        'password' => $data->password,
                        'mini_code' => $data->mini_code,
                        'dt_code' => $data->dt_code,
                        'mini_name' => $data->mini_name,
                        'dt_name' => $data->dt_name,
                    ]);
                }
            }
        }
    }

    public static function checkMiniNoResponse()
    {
        $orderCustomers = OrderCustomer::where('status','คำสั่งซื้อใหม่')
            ->where(\DB::Raw('TIMESTAMPDIFF(MINUTE,NOW(),exp_time)'),'<=',0)
            ->get();
        foreach ($orderCustomers as $key => $orderCustomer) {
            $orderCustomer->update([
                'status' => 'ยกเลิกโดยระบบ'
            ]);
            CoreLineFunction::pushMessageToCustomerCancleBySystem($orderCustomer);
        }
    }

}
