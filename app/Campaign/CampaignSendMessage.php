<?php

namespace YellowProject\Campaign;

use Illuminate\Database\Eloquent\Model;

class CampaignSendMessage extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_campaign_send_message';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'campaign_id',
        'mid',
    ];
}
