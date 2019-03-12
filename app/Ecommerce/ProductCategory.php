<?php

namespace YellowProject\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Product;
use YellowProject\Ecommerce\Category;

class ProductCategory extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_product_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ecommerce_product_id',
		'ecommerce_category_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'ecommerce_product_id','id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'ecommerce_category_id','id');
    }

}
