<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

class BotTrain extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_bot_train';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question',
        'answer',
        'mid',
        'user_id',
    ];
}
