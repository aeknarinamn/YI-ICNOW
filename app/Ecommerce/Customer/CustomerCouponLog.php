<?php

namespace YellowProject\Ecommerce\Customer;

use Illuminate\Database\Eloquent\Model;

class CustomerCouponLog extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_customer_coupon_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_coupon_id',
    	'ecommerce_customer_id',
    	'action',
    ];
}
