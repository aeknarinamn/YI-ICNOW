<?php

namespace YellowProject\ICNOW\Images;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_icnow_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'img_url',
        'type',
    ];

    public static function checkFolder($path)
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
