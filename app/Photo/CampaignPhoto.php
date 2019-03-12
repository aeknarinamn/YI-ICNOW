<?php

namespace YellowProject\Photo;

use Illuminate\Database\Eloquent\Model;

class CampaignPhoto extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_campaign_photo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'img_url',
    ];

    public static function checkFolderDefaultPath()
    {
        if (!\File::isDirectory('campaign_file/photo')){
            self::createFolder('campaign_file/photo');
        }
    }

    public static function createFolder($path_save_image)
    {
        $result = \File::makeDirectory($path_save_image, 0775, true);
        return $result;
    }
}
