<?php

namespace YellowProject\Ecommerce\Coupon;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Category;

class CouponProductCategory extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_coupon_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ecommerce_coupon_id',
        'ecommerce_product_category_id',
    ];

    public function productCategory()
    {
        return $this->belongsTo(Category::class,'ecommerce_product_category_id','id');
    }
}
