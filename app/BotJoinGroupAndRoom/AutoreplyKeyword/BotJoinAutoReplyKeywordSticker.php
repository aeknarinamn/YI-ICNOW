<?php

namespace YellowProject\BotJoinGroupAndRoom\AutoreplyKeyword;

use Illuminate\Database\Eloquent\Model;

class BotJoinAutoReplyKeywordSticker extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'botjoin_auto_reply_sticker';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
		'packageId',
		'stickerId',
        'display',//
    ];
}
