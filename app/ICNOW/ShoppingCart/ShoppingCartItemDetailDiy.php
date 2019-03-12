<?php

namespace YellowProject\ICNOW\ShoppingCart;

use Illuminate\Database\Eloquent\Model;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailDiyItem;

class ShoppingCartItemDetailDiy extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_icnow_shopping_cart_item_detail_diy';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shopping_cart_item_id',
        'person_in_party',
        'other_option',
        'product_focus',
        'comment',
    ];

    public function shoppingCartItemDetailDiyItems()
    {
        return $this->hasMany(ShoppingCartItemDetailDiyItem::class,'shopping_cart_item_detail_diy_id','id');
    }
}
