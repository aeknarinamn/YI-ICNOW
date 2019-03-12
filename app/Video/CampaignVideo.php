<?php

namespace YellowProject\Video;

use Illuminate\Database\Eloquent\Model;

class CampaignVideo extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_campaign_video';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'video_url',
    ];

    public static function checkFolderDefaultPath()
    {
        if (!\File::isDirectory('campaign_file/video')){
            self::createFolder('campaign_file/video');
        }
    }

    public static function createFolder($path_save_image)
    {
        $result = \File::makeDirectory($path_save_image, 0775, true);
        return $result;
    }
}
