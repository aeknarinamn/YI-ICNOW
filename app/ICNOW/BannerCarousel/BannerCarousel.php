<?php

namespace YellowProject\ICNOW\BannerCarousel;

use Illuminate\Database\Eloquent\Model;
use YellowProject\ICNOW\BannerCarousel\BannerCarouseImages;

class BannerCarousel extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_icnow_banner_carousel';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'carousel_name',
        'delay_time',
        'is_active',
    ];

    public function bannerCarouseImages()
    {
        return $this->hasMany(BannerCarouseImages::class,'icnow_banner_carousel_id','id');
    }

    public static function genData()
    {
        \YellowProject\ICNOW\BannerCarousel\BannerCarousel::truncate();
        \YellowProject\ICNOW\BannerCarousel\BannerCarouseImages::truncate();
        BannerCarousel::create([
            'carousel_name' => 'Home',
            'delay_time' => 2,
            'is_active' => 1,
        ]);
    }
}
