<?php

namespace YellowProject\Campaign;

use Illuminate\Database\Eloquent\Model;

class ScheduleCampaign extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_schedule_campaign';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'campaign_id',
        'status',
    ];
}
