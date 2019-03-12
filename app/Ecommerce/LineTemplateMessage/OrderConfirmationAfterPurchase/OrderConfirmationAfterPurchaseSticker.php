<?php

namespace YellowProject\Ecommerce\LineTemplateMessage\OrderConfirmationAfterPurchase;

use Illuminate\Database\Eloquent\Model;

class OrderConfirmationAfterPurchaseSticker extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_line_temp_order_conf_after_purchase_sticker';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
		'packageId',
		'stickerId',
        'display',//
    ];
}
