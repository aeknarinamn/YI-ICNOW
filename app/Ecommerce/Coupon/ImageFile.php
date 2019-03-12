<?php

namespace YellowProject\Ecommerce\Coupon;

use Illuminate\Database\Eloquent\Model;

class ImageFile extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_coupon_image';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'img_url',
        'img_size',
		'type',
    ];

    public static function checkFolderDefaultPath()
    {
        if (!\File::isDirectory('ecommerce')){
            self::createFolder('ecommerce');
        }else{
            if (!\File::isDirectory('ecommerce/coupon')){
                self::createFolder('ecommerce/coupon');
            }
        }
    }

    public static function createFolder($path_save_image)
    {
        $result = \File::makeDirectory($path_save_image, 0775, true);
        return $result;
    }
}
