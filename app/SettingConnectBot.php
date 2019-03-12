<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

class SettingConnectBot extends Model
{
    public $timestamps = true;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected   $table  =  'dim_setting_connect_bot';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected   $fillable = [
        'url_bot_train',
        'url_bot_train_remove',
        'is_active',
    ];
}
