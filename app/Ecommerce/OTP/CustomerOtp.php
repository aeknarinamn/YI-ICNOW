<?php

namespace YellowProject\Ecommerce\OTP;

use Illuminate\Database\Eloquent\Model;

class CustomerOtp extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_customer_otp';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_customer_id',
    	'otp_ref',
        'otp_code',
		'is_active',
    ];

    public static function genOtp($length = 6)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        // $otp = rand(000000, 999999);
        return $randomString;
    }

    public static function genRefOtp($length = 6)
    {
        // $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function showPhoneNumberView($phoneNumber)
    {
        $str_1 = substr($phoneNumber,0,-8);
        $str_2 = substr($phoneNumber,3,-4);
        $str_3 = substr($phoneNumber,7,-2);
        $showPhoneNumber = $str_1." ".$str_2." ".$str_3."xx";
        return $showPhoneNumber;
    }
}
