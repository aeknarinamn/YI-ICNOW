<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;
use Carbon\Carbon;

class LineSettingBusiness extends Model
{
    use SoftDeletes;
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'line_setting_business';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'active',
		'channel_id',
		'channel_secret',
		'channel_access_token',
		'name',
        'refresh_token',
        'expire',
        'line_url_calback',
        'max_message'
    ];


    public static function checkDuplicate($channelId)
    {
        $item = LineSettingBusiness::where('channel_id',$channelId)->first();
        if (is_null($item)) {
            return false;
        } else {
            return true;
        }
    }


    public function setExpireAttribute($epochMsec)
    {
        if ($epochMsec != "") {
            $data = $this->attributes['expire'];
            $expire_date = Carbon::createFromFormat('Y-m-d H:i:s', $data);
            
            $this->attributes['expire'] =  $expire_date->addSeconds($epochMsec)->format('Y-m-d H:i:s');

        } else {
            $this->attributes['expire'] = null;
        }
    }

    public static function issueToken($lineSettingBusiness)
    {
        $datas = collect();
        // $body = collect([
        //     "client_id"        => $lineSettingBusiness->channel_id,
        //     "client_secret"    => $lineSettingBusiness->channel_secret,
        //     "grant_type"        => "client_credentials",    
        // ]);
        $body = "grant_type=client_credentials&client_id=".$lineSettingBusiness->channel_id."&client_secret=".$lineSettingBusiness->channel_secret;
        $datas->put('token', $lineSettingBusiness->channel_access_token);
        $datas->put('data', $body);
        $datas->put('id', $lineSettingBusiness->id);

        $rs = self::sent($datas);

        return $rs;
    }
    


    private static function sent($arrDatas)
    {
        $url  = "https://api.line.me/v2/oauth/accessToken";
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $arrDatas['data'],
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded",
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            Log::debug('cURL Error #: =>'. $err);
        } else {
            $rs = self::updateData($arrDatas['id'], $response);

            Log::debug(' accessToken =>'. $response);
            
            return $rs;
        }
    }

    private static function updateData($id, $arrDatas)
    {
        $lineSettingBusiness  = LineSettingBusiness::find($id);
        
        if ($lineSettingBusiness) {
            $data = json_decode($arrDatas);
            if(isset($data->access_token) && isset($data->expires_in) && isset($data->token_type)) {
                 $lineSettingBusiness->update([
                    'channel_access_token'  => $data->access_token,
                    'expire'                => $data->expires_in,
                ]);
                
                return true;
            } else {
                return false;
            }

        }  else {
            return false;
        }
    }


    // public function getExpireAttribute()
    // {
    //      $valueSentDate = new Carbon($this->expire);
    //      $sent_dates = Carbon::createFromFormat('Y-m-d H:i:s', $valueSentDate)->format('d/m/Y H:i');
    //      $valueLastDate = new Carbon($this->last_sent_date);
    //      $last_dates = Carbon::createFromFormat('Y-m-d H:i:s', $valueLastDate)->format('d/m/Y H:i');

    //      return  $sent_dates . ' ~ '. $last_dates;
    // }


       
}
