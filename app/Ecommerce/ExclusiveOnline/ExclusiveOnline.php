<?php

namespace YellowProject\Ecommerce\ExclusiveOnline;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\ExclusiveOnline\ExclusiveOnlineVideo;
use YellowProject\Ecommerce\ExclusiveOnline\ExclusiveOnlinePdf;
use YellowProject\Ecommerce\ExclusiveOnline\ExclusiveOnlineUser;

class ExclusiveOnline extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_exclusive_online';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'course_name',
    	'desc',
        'avaliable_strat_date',
		'avaliable_end_date',
		'hours_duration',
		'ep',
		'status',
    ];

    public function videoFiles()
    {
        return $this->hasMany(ExclusiveOnlineVideo::class,'exclusive_online_id','id');
    }

    public function pdfFiles()
    {
        return $this->hasMany(ExclusiveOnlinePdf::class,'exclusive_online_id','id');
    }

    public function customers()
    {
        return $this->hasMany(ExclusiveOnlineUser::class,'exclusive_online_id','id');
    }
}
