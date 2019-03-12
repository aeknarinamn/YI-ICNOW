<?php

namespace YellowProject\ICNOW\ShoppingCart;

use Illuminate\Database\Eloquent\Model;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItem;

class ShoppingCart extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_icnow_shopping_cart';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'line_user_id',
        'is_active',
        'is_product_update',
    ];

    public function shoppingCartItems()
    {
        return $this->hasMany(ShoppingCartItem::class,'shopping_cart_id','id');
    }
}
