<?php

namespace YellowProject\ICNOW\Product;

use Illuminate\Database\Eloquent\Model;

class ProductDiyProductFocus extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_icnow_product_diy_product_focus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'icnow_product_id',
        'value',
        'img_url'
    ];
}
