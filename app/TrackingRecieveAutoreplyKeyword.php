<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\TrackingAutoreplyKeyword;

class TrackingRecieveAutoreplyKeyword extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_recieve_tracking_auto_reply_keyword';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tracking_auto_reply_keyword_id',
		'line_user_id',
		'ip',
		'device',
    ];

    public function trackingAutoreplyKeyword()
    {
        return $this->belongsTo(TrackingAutoreplyKeyword::class, 'tracking_auto_reply_keyword_id', 'id');
    }
}
