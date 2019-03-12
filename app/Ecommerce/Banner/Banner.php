<?php

namespace YellowProject\Ecommerce\Banner;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Banner\BannerItem;

class Banner extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_banner';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'delay_time',
        'type',
    ];

    public function items()
    {
        return $this->hasMany(BannerItem::class,'ecommerce_banner_id','id');
    }
}
