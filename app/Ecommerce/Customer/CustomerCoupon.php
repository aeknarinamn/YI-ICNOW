<?php

namespace YellowProject\Ecommerce\Customer;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Coupon\Coupon;

class CustomerCoupon extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_customer_coupon';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_coupon_id',
    	'ecommerce_customer_id',
    	'status',
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class,'ecommerce_coupon_id','id');
    }
}
