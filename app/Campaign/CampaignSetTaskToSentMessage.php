<?php

namespace YellowProject\Campaign;

use Illuminate\Database\Eloquent\Model;

class CampaignSetTaskToSentMessage extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_campaign_set_task_to_send_message';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'campaign_id',
        'type',
        'check_status',
    ];
}
