<?php

namespace YellowProject\DownloadFile;

use Illuminate\Database\Eloquent\Model;

class DownloadFile extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_download_file';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_name',
		'file_link_path',
		'file_type',
        'status',
		'deleted_at',
    ];

    public static function checkFolderSubscriber($path)
    {
        $path = public_path().'/'.$path;
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
