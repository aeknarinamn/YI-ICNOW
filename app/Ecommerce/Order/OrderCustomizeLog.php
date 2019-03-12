<?php

namespace YellowProject\Ecommerce\Order;

use Illuminate\Database\Eloquent\Model;

class OrderCustomizeLog extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_order_customize_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'user_id',
    	'dt_id',
    	'order_id',
        'product_id',
    	'original_price',
        'price',
		'quanlity',
		'tax_amount',
		'tax_percent',
		'discount_amount',
		'total',
    ];

}
