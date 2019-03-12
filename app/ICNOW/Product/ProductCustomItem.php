<?php

namespace YellowProject\ICNOW\Product;

use Illuminate\Database\Eloquent\Model;

class ProductCustomItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_icnow_product_custom_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'icnow_product_custom_set_id',
        'value',
        'img_url',
        'default_unit',
        'price',
    ];
}
