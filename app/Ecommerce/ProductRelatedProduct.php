<?php

namespace YellowProject\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class ProductRelatedProduct extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_product_related_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ecommerce_product_id',
		'value',
    ];

}
