<?php

namespace YellowProject\Campaign;

use Illuminate\Database\Eloquent\Model;

class ScheduleSet extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_schedule_set';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'campaign_id',
        'mid',
		'send_time',
		'status',
    ];
}
