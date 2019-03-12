<?php

namespace YellowProject\Ecommerce\Coupon;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Coupon\Coupon;
use YellowProject\Ecommerce\Coupon\CouponItem;
use YellowProject\Ecommerce\Coupon\ImageFile;

class CouponSection extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_coupon_section';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ecommerce_coupon_id',
        'section_name',
        'img_size',
        'img_id',
    ];

    public function coupon()
    {
        return $this->belongsto(Coupon::class,'ecommerce_coupon_id','id');
    }

    public function couponItems()
    {
        return $this->hasMany(CouponItem::class,'ecommerce_coupon_section_id','id');
    }

    public function imageFile()
    {
        return $this->belongsto(ImageFile::class,'img_id','id');
    }
}
