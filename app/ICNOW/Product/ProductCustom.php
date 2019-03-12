<?php

namespace YellowProject\ICNOW\Product;

use Illuminate\Database\Eloquent\Model;
use YellowProject\ICNOW\Product\ProductCustomItem;

class ProductCustom extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_icnow_product_custom';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'icnow_product_id',
        'group_name',
        'volumn',
        'unit',
    ];

    public function productCustomItems()
    {
        return $this->hasMany(ProductCustomItem::class,'icnow_product_custom_set_id','id');
    }
}
