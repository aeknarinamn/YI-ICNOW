<?php

namespace YellowProject\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\ProductMeta;
use YellowProject\Ecommerce\ProductCategory;
use YellowProject\Ecommerce\ProductDiscount;
use YellowProject\Ecommerce\ProductRelatedProduct;
use YellowProject\Ecommerce\ProductImage;
use YellowProject\TrackingBc;

class Product extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'desc',
		'short_desc',
		'start_date',
		'end_date',
        'sku',
        'price',
        'price_unit',
        'shipping_cost',
        'vat',
        'source_order',
        'out_of_stock_status',
        'status',
        'reward_point',
        'product_brand',
        'product_name',
        'product_size',
        'tracking_bc_id',
    ];

    public function productMeta()
    {
        return $this->belongsTo(ProductMeta::class,'ecommerce_product_id','id');
    }

    public function productDiscount()
    {
        return $this->belongsTo(ProductDiscount::class,'id','ecommerce_product_id');
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class,'ecommerce_product_id','id');
    }

    public function productRelatedProducts()
    {
        return $this->hasMany(ProductRelatedProduct::class,'ecommerce_product_id','id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class,'ecommerce_product_id','id');
    }

    public function trackingBc()
    {
        return $this->belongsto(TrackingBc::class,'tracking_bc_id','id');
    }
}
