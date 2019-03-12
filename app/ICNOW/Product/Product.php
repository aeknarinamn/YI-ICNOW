<?php

namespace YellowProject\ICNOW\Product;

use Illuminate\Database\Eloquent\Model;
use YellowProject\ICNOW\Product\ProductImages;
use YellowProject\ICNOW\Product\ProductDiyPerson;
use YellowProject\ICNOW\Product\ProductDiyProductFocus;
use YellowProject\ICNOW\Product\ProductDiyOtherOption;
use YellowProject\ICNOW\Product\ProductPartySet;
use YellowProject\ICNOW\Product\ProductCustom;

class Product extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_icnow_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_name',
        'section_id',
        'product_desc',
        'sku',
        'price',
        'special_price',
        'special_start_date',
        'special_end_date',
        'is_active',
        'sort_order',
    ];

    public function productImages()
    {
        return $this->hasMany(ProductImages::class,'icnow_product_id','id');
    }

    public function productDiyPersons()
    {
        return $this->hasMany(ProductDiyPerson::class,'icnow_product_id','id');
    }

    public function productDiyProductFocuses()
    {
        return $this->hasMany(ProductDiyProductFocus::class,'icnow_product_id','id');
    }

    public function productDiyOtherOptions()
    {
        return $this->hasMany(ProductDiyOtherOption::class,'icnow_product_id','id');
    }

    public function productPartySets()
    {
        return $this->hasMany(ProductPartySet::class,'icnow_product_id','id');
    }

    public function productCustoms()
    {
        return $this->hasMany(ProductCustom::class,'icnow_product_id','id');
    }
}
