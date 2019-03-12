<?php

namespace YellowProject\Bot;

use Illuminate\Database\Eloquent\Model;

class SettingBot extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_setting_bot';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot_train_url',
		'bot_remove_url',
        'bot_reply_url',
		'bot_restart_url',
		'conf',
        'is_active',
		'is_open_bot',
    ];

}
