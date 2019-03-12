<?php

namespace YellowProject\Bot;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Bot\TrainBotCsvLog;

class TrainBotCsv extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_train_bot_csv';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'conf',
    ];

    public function trainBotCsvLogs()
    {
        return $this->hasMany(TrainBotCsvLog::class,'train_bot_csv_id','id');
    }
}
