<?php

namespace YellowProject\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Image\Image;

class ProductImage extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_product_img';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ecommerce_product_id',
        'product_img_id',
		'name',
		'seq',
		'is_base',
		'is_small',
		'is_thumbnail',
    ];

    public function image()
    {
        return $this->belongsTo(Image::class,'product_img_id','id');
    }
}
