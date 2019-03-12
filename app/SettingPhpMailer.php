<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SettingPhpMailer extends Model
{
	use SoftDeletes;
    public $timestamps = true;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected   $table  =  'dim_php_mailer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected   $fillable = [
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_driver',
        'mail_encryption',
        'is_active',
    ];
}
