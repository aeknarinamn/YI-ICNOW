<?php

namespace YellowProject\Ecommerce\ProductRecommend;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Product;

class ProductRecommend extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_product_recommend';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name',
    	'product_1',
        'product_2',
        'product_3',
        'product_4',
        'is_active',
        'start_date',
        'end_date',
    ];

    public function product1()
    {
        return $this->belongsTo(Product::class,'product_1','id');
    }

    public function product2()
    {
        return $this->belongsTo(Product::class,'product_2','id');
    }

    public function product3()
    {
        return $this->belongsTo(Product::class,'product_3','id');
    }

    public function product4()
    {
        return $this->belongsTo(Product::class,'product_4','id');
    }
}
