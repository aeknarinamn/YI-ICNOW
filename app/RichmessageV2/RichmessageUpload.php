<?php

namespace YellowProject\RichmessageV2;

use Illuminate\Database\Eloquent\Model;

class RichmessageUpload extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_richmessage_upload_img';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'img_url',
        'img_size',
    ];

    public static function checkFolderDefaultPath($path)
    {
        if (!\File::isDirectory($path)){
            self::createFolder($path);
        }
    }

    public static function createFolder($path_save_image)
    {
        $result = \File::makeDirectory($path_save_image, 0775, true);
        return $result;
    }
}
