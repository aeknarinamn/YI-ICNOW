<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

class AutoReplyLocation extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_auto_reply_location';
    //protected $appends = ['show_keyword'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'alt_text',
        'folder_id',
		'limit_corosel',
		'limit_time_confirmation',
		'confirmation_name',
		'confirmation_reply',
		'is_active',
		'is_confirmation',
    ];
}
