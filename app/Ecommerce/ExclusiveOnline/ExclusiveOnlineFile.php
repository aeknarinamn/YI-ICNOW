<?php

namespace YellowProject\Ecommerce\ExclusiveOnline;

use Illuminate\Database\Eloquent\Model;

class ExclusiveOnlineFile extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_exclusive_online_file';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'link_path',
    	'type',
    ];

    public static function checkFolderExclusiveOnlineFile()
    {
        if (!\File::isDirectory('ecommerce')){
            self::createFolder('ecommerce');
        }else{
            if (!\File::isDirectory('ecommerce/exclusive_online')){
                self::createFolder('ecommerce/exclusive_online');
            }
        }
    }

    public static function createFolder($path_save_image)
    {
        $result = \File::makeDirectory($path_save_image, 0775, true);
        return $result;
    }

}
