<?php

namespace YellowProject\ICNOW\BannerCarousel;

use Illuminate\Database\Eloquent\Model;

class BannerCarouseImages extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_icnow_banner_carousel_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'icnow_banner_carousel_id',
        'image_url',
    ];
}
