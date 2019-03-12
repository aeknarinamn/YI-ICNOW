<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Coupon;
use YellowProject\SB\Coupon as SBCoupon;

class TrackingBc extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_tracking_bc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
    		'original_url',
    		'tracking_source',
    		'tracking_campaign',
    		'tracking_ref',
    		'generated_full_url',
    		'generated_short_url',
        'desc',
        'is_route_name',
    		'campaign_id',
        'is_line_liff',
    ];

    public function coupon()
    {
        return $this->hasMany(Coupon::class, 'tracking_bc_id', 'id');
    }

    public function couponSb()
    {
        return $this->hasMany(SBCoupon::class, 'tracking_bc_id', 'id');
    }

    public static function generateCode($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function getClientIps()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
    }

    public static function getGeoLocation($ip)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "172.104.63.108:8080/json/".$ip,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/vnd.maxmind.com-insights+json; charset=UTF-8; version=2.1",
            "postman-token: 214a7ab7-4c60-7c03-f6d2-81813eac8fab"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return json_decode($response);
    }

    // public function trackingRecieveOas()
    // {
    //     return $this->hasMany(TrackingRecieveOa::class,'tracking_oa_id','id');
    // }
}
