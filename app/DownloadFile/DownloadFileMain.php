<?php

namespace YellowProject\DownloadFile;

use Illuminate\Database\Eloquent\Model;

class DownloadFileMain extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_download_file';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'main_id',
		'type',
        'requests',
        'is_active',
    ];
}
