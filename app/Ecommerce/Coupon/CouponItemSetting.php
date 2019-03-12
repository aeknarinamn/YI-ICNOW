<?php

namespace YellowProject\Ecommerce\Coupon;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Coupon\CouponItem;

class CouponItemSetting extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_coupon_item_setting';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ecommerce_coupon_item_id',
        'key',
        'label',
        'type',
        'value',
    ];

    public function couponItem()
    {
        return $this->belongsto(CouponItem::class,'ecommerce_coupon_item_id','id');
    }
}
