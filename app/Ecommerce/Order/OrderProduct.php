<?php

namespace YellowProject\Ecommerce\Order;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Product;

class OrderProduct extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_order_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
