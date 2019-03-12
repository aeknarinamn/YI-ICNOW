<?php

namespace YellowProject\RichmessageV2;

use Illuminate\Database\Eloquent\Model;
use YellowProject\RichmessageV2\RichmessageAreaAction;
use YellowProject\RichmessageV2\RichmessageAreaBounds;

class RichmessageArea extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_rich_message_areas_v2';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rich_message_id',
    ];

    public function action()
    {
        return $this->hasOne(RichmessageAreaAction::class,'rich_message_areas_id','id');
    }

    public function bound()
    {
        return $this->hasOne(RichmessageAreaBounds::class,'rich_message_areas_id','id');
    }
}
