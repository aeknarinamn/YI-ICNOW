<?php

namespace YellowProject\Ecommerce\Banner;

use Illuminate\Database\Eloquent\Model;

class BannerItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_banner_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_banner_id',
    	'img_url',
    ];
}
