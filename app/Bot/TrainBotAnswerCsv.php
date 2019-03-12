<?php

namespace YellowProject\Bot;

use Illuminate\Database\Eloquent\Model;

class TrainBotAnswerCsv extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_train_bot_answer_csv';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fact_bot_train_csv_id',
		'answer',
    ];

    // public function imageFile1()
    // {
    //     return $this->belongsTo(ImageFile::class,'img_id1','id');
    // }
}
