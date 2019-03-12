<?php

namespace YellowProject\Ecommerce\Image;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_image_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'img_url',
		'img_size',
    ];

    public static function checkFolderEcomProduct()
    {
        if (!\File::isDirectory('ecommerce')){
            self::createFolder('ecommerce');
        }else{
            if (!\File::isDirectory('ecommerce/product')){
                self::createFolder('ecommerce/product');
            }
        }
    }

    public static function checkFolderEcomPayment()
    {
        if (!\File::isDirectory('ecommerce')){
            self::createFolder('ecommerce');
        }else{
            if (!\File::isDirectory('ecommerce/payment')){
                self::createFolder('ecommerce/payment');
            }
        }
    }

    public static function createFolder($path_save_image)
    {
        $result = \File::makeDirectory($path_save_image, 0775, true);
        return $result;
    }

}
