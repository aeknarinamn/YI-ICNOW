<?php

namespace YellowProject\ICNOW\ShoppingCart;

use Illuminate\Database\Eloquent\Model;

class ShoppingCartItemDetailDiyItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_icnow_shopping_cart_item_detail_diy_item';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shopping_cart_item_detail_diy_id',
        'value',
    ];
}
