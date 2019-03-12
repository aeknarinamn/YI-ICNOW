<?php

namespace YellowProject\Ecommerce\ShoppingCart;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\ShoppingCart\ShoppingCartItem;

class ShoppingCart extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_shopping_cart';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_customer_id',
    	'quanlity',
        'coupon_code',
		'coupon_discount',
		'is_survey_discount',
		'survey_discount',
		'summary',
		'reward_point',
    ];

    public function items()
    {
        return $this->hasMany(ShoppingCartItem::class,'ecommerce_shopping_cart_id','id');
    }
}
