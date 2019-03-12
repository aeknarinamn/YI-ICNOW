<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

class ImageFile extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_image_file';

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
        if (!\File::isDirectory('file_uploads')){
            self::createFolder('file_uploads');
        }else{
            if (!\File::isDirectory('file_uploads/img_upload')){
                self::createFolder('file_uploads/img_upload');
            }
        }
    }

    public static function checkFolderEcomProduct()
    {
        if (!\File::isDirectory('ecom_line')){
            self::createFolder('ecom_line');
        }else{
            if (!\File::isDirectory('ecom_line/ecom_shopping_line')){
                self::createFolder('ecom_line/ecom_shopping_line');
            }
        }
    }

    public static function checkFolderEcomPayment()
    {
        if (!\File::isDirectory('ecom_line')){
            self::createFolder('ecom_line');
        }else{
            if (!\File::isDirectory('ecom_line/ecom_payment_line')){
                self::createFolder('ecom_line/ecom_payment_line');
            }
        }
    }

    public static function checkFolderCoupon()
    {
        if (!\File::isDirectory('coupon_line/coupon_special_deal/original_image')){
            self::createFolder('coupon_line/coupon_special_deal/original_image');
        }
        // if (!\File::isDirectory('coupon_line')){
        //     self::createFolder('coupon_line');
        // }else{
        //     if (!\File::isDirectory('coupon_line/coupon_special_deal')){
        //         self::createFolder('coupon_line/coupon_special_deal');
        //     }else{
        //         if (!\File::isDirectory('coupon_line/coupon_special_deal/original_image')){
        //             self::createFolder('coupon_line/coupon_special_deal/original_image');
        //         }
        //     }
        // }
    }

    public static function createFolder($path_save_image)
    {
        $result = \File::makeDirectory($path_save_image, 0775, true);
        return $result;
    }
}
