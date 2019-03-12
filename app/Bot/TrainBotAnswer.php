<?php

namespace YellowProject\Bot;

use Illuminate\Database\Eloquent\Model;

class TrainBotAnswer extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_bot_train_answer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fact_bot_train_id',
		'answer',
    ];

    // public function imageFile1()
    // {
    //     return $this->belongsTo(ImageFile::class,'img_id1','id');
    // }
}
