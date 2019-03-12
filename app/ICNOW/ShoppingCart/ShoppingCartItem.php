<?php

namespace YellowProject\ICNOW\ShoppingCart;

use Illuminate\Database\Eloquent\Model;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailDiy;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailPartySet;

class ShoppingCartItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_icnow_shopping_cart_item';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shopping_cart_id',
        'line_user_id',
        'product_id',
        'product_name',
        'section_id',
        'product_desc',
        'sku',
        'price',
        'before_price_discount',
        'special_price',
        'special_start_date',
        'special_end_date',
        'retial_price',
        'quantity',
    ];

    public function shoppingCartItemDetailDiy()
    {
        return $this->hasOne(ShoppingCartItemDetailDiy::class,'shopping_cart_item_id','id');
    }

    public function shoppingCartItemDetailPartySets()
    {
        return $this->hasMany(ShoppingCartItemDetailPartySet::class,'shopping_cart_item_id','id');
    }
}
