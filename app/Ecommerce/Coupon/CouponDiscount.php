<?php

namespace YellowProject\Ecommerce\Coupon;

use Illuminate\Database\Eloquent\Model;

class CouponDiscount extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_coupon_discount';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ecommerce_coupon_id',
        'type',
        'discount',
        'total_amount',
    ];
}
