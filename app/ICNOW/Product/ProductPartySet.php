<?php

namespace YellowProject\ICNOW\Product;

use Illuminate\Database\Eloquent\Model;
use YellowProject\ICNOW\Product\ProductPartySetItem;

class ProductPartySet extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_icnow_product_party_set';

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

    public function productPartySetItems()
    {
        return $this->hasMany(ProductPartySetItem::class,'icnow_product_party_set_id','id');
    }
}
