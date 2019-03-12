<?php

namespace YellowProject\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\CategoryImage;
use YellowProject\Ecommerce\ProductCategory;

class Category extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'is_sub_category',
    	'main_category_id',
        'name',
		'desc',
		'meta_title',
		'meta_keywords',
		'meta_desc',
		'status',
    ];

    public function categoryImages()
    {
        return $this->hasMany(CategoryImage::class,'ecommerce_category_id','id');
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class,'ecommerce_category_id','id');
    }

}
