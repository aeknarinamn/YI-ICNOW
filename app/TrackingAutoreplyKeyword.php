<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\TrackingRecieveAutoreplyKeyword;

class TrackingAutoreplyKeyword extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_tracking_auto_reply_keyword';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
		'original_url'
    ];

    public function trackingRecieveAutoreplyKeywords()
    {
        return $this->hasMany(TrackingRecieveAutoreplyKeyword::class,'tracking_auto_reply_keyword_id','id');
    }
}
