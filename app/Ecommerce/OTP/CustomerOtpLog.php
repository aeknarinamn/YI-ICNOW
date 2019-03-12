<?php

namespace YellowProject\Ecommerce\OTP;

use Illuminate\Database\Eloquent\Model;

class CustomerOtpLog extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_customer_otp_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_customer_id',
    	'otp_ref',
        'otp_code',
		'flag_status',
    ];
}
