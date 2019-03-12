<?php

namespace YellowProject\ICNOW\ShoppingCart;

use Illuminate\Database\Eloquent\Model;

class ShoppingCartItemDetailPartySetItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_icnow_shopping_cart_item_detail_party_set_item';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shopping_cart_item_party_set_id',
        'item_name',
        'item_value',
    ];
}
