<?php

namespace YellowProject\MessageFile;

use Illuminate\Database\Eloquent\Model;

class MessageFile extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_message_file';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'img_url',
		'img_size',
    ];

    public static function checkFolderMessageFile()
    {
        if (!\File::isDirectory('message-file')){
            self::createFolder('message-file');
        }
    }

    public static function createFolder($path_save_image)
    {
        $result = \File::makeDirectory($path_save_image, 0775, true);
        return $result;
    }
}
