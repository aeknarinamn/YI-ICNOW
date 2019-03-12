<?php

namespace YellowProject\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class ProductMeta extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_product_meta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ecommerce_product_id',
		'title',
		'keyword',
		'desc',
    ];

}
