<?php

namespace YellowProject\Campaign;

use Illuminate\Database\Eloquent\Model;

class CampaignSchedule extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'campaign_schedule';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'schedule_id',
        'schedule_type',
		'schedule_start_date',
		'schedule_end_date',
		'schedule_recurrence_type',
		'schedule_recurrence_sun',
		'schedule_recurrence_mon',
		'schedule_recurrence_tue',
		'schedule_recurrence_wed',
		'schedule_recurrence_thu',
		'schedule_recurrence_fri',
		'schedule_recurrence_sat',
		'schedule_recurrence_running',
        'schedule_schedule_send_time',
    ];
}
