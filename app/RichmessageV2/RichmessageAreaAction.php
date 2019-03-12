<?php

namespace YellowProject\RichmessageV2;

use Illuminate\Database\Eloquent\Model;

class RichmessageAreaAction extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_rich_message_areas_action_v2';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rich_message_areas_id',
		'data',
		'type',
    ];
}
