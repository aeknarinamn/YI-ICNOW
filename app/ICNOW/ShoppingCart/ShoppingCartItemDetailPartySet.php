<?php

namespace YellowProject\ICNOW\ShoppingCart;

use Illuminate\Database\Eloquent\Model;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailPartySetItem;

class ShoppingCartItemDetailPartySet extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_icnow_shopping_cart_item_detail_party_set';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shopping_cart_item_id',
        'group_name',
        'choose_item',
        'max_item',
    ];

    public function shoppingCartItemDetailPartySetItems()
    {
        return $this->hasMany(ShoppingCartItemDetailPartySetItem::class,'shopping_cart_item_party_set_id','id');
    }
}