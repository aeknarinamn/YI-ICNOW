<?php

namespace YellowProject\Bot;

use Illuminate\Database\Eloquent\Model;

class TrainBotCsvLogItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_train_bot_csv_log_item';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'train_bot_csv_log_id',
        'question',
        'answer',
        'status',
    ];
}
