<?php

namespace YellowProject\Ecommerce\Coupon;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Coupon\CouponSection;
use YellowProject\Ecommerce\Coupon\CouponItemSetting;
use YellowProject\Ecommerce\Coupon\CouponItemCss;

class CouponItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_coupon_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ecommerce_coupon_section_id',
        'title',
        'el_id',
        'index',
        'type',
        'height',
        'width',
        'x',
        'y',
    ];

    public function couponItemCss()
    {
        return $this->hasMany(CouponItemCss::class,'ecommerce_coupon_item_id','id');
    }

    public function couponItemSettings()
    {
        return $this->hasMany(CouponItemSetting::class,'ecommerce_coupon_item_id','id');
    }

    public function couponSection()
    {
        return $this->belongsto(CouponSection::class,'ecommerce_coupon_section_id','id');
    }
}
