<?php

namespace YellowProject\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_product_discount';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ecommerce_product_id',
        'priority',
		'discount_price',
		'start_date',
		'end_date',
    ];

}
