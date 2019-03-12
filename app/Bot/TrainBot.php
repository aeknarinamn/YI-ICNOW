<?php

namespace YellowProject\Bot;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Bot\TrainBotAnswer;

class TrainBot extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_train_bot';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'question',
    ];

    public function botTrainAnswers()
    {
        return $this->hasMany(TrainBotAnswer::class,'fact_bot_train_id','id');
    }
}
