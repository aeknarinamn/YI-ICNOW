<?php

namespace YellowProject\Ecommerce\Coupon;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Product;

class CouponProduct extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_coupon_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ecommerce_coupon_id',
        'ecommerce_product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'ecommerce_product_id','id');
    }
}
