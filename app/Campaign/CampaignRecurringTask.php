<?php

namespace YellowProject\Campaign;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Campaign;
use YellowProject\Campaign\CampaignSchedule;

class CampaignRecurringTask extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_campaign_schedule_recurring_task';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'campaign_schedule_id',
        'campaign_id',
		'schedule_start_date',
		'schedule_end_date',
		'schedule_send_date',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

    public function campaignSchedule()
    {
        return $this->belongsTo(CampaignSchedule::class, 'campaign_schedule_id', 'id');
    }
}
