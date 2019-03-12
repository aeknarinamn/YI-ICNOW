<?php

namespace YellowProject\Ecommerce\ExclusiveOnline;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\ExclusiveOnline\ExclusiveOnlineFile;

class ExclusiveOnlineVideo extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_exclusive_online_video';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'exclusive_online_id',
        'ecommerce_exclusive_online_file_id',
    	'label',
    ];

    public function file()
    {
        return $this->belongsTo(ExclusiveOnlineFile::class,'ecommerce_exclusive_online_file_id','id');
    }
}
