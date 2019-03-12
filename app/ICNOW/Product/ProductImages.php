<?php

namespace YellowProject\ICNOW\Product;

use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_icnow_product_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'icnow_product_id',
        'img_url',
    ];
}
