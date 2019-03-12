<?php

namespace YellowProject\Ecommerce\ShoppingCart;

use Illuminate\Database\Eloquent\Model;

class ShoppingCartItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_shopping_cart';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_shopping_cart_id',
    	'product_id',
        'product_name',
        'product_img_url',
        'short_name',
        'discount_price',
		'price',
        'quanlity',
		'summary',
    ];
}
