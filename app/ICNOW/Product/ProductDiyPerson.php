<?php

namespace YellowProject\ICNOW\Product;

use Illuminate\Database\Eloquent\Model;

class ProductDiyPerson extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_icnow_product_diy_person';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'icnow_product_id',
        'value',
    ];
}
