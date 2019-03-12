<?php

namespace YellowProject\Ecommerce\Customer;

use Illuminate\Database\Eloquent\Model;

class CustomerImage extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_customer_img';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'img_url',
    	'img_size',
    ];

    public static function checkFolderEcomCustomer()
    {
        if (!\File::isDirectory('ecommerce')){
            self::createFolder('ecommerce');
        }else{
            if (!\File::isDirectory('ecommerce/customer')){
                self::createFolder('ecommerce/customer');
            }
        }
    }

    public static function createFolder($path_save_image)
    {
        $result = \File::makeDirectory($path_save_image, 0775, true);
        return $result;
    }
}
