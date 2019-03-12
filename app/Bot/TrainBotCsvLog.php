<?php

namespace YellowProject\Bot;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Bot\TrainBotCsvLogItem;

class TrainBotCsvLog extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_train_bot_csv_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'train_bot_csv_id',
    ];

    public function trainBotCsvLogItems()
    {
        return $this->hasMany(TrainBotCsvLogItem::class,'train_bot_csv_log_id','id');
    }
}
