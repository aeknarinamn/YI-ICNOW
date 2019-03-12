<?php

namespace YellowProject\Ecommerce\Banner;

use Illuminate\Database\Eloquent\Model;

class BannerImage extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_banner_image';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'img_url',
    	'img_size',
    ];

    public static function checkFolderEcomBanner()
    {
        if (!\File::isDirectory('ecommerce')){
            self::createFolder('ecommerce');
        }else{
            if (!\File::isDirectory('ecommerce/banner')){
                self::createFolder('ecommerce/banner');
            }
        }
    }

    public static function createFolder($path_save_image)
    {
        $result = \File::makeDirectory($path_save_image, 0775, true);
        return $result;
    }
}
