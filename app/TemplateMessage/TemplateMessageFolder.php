<?php

namespace YellowProject\TemplateMessage;

use Illuminate\Database\Eloquent\Model;

class TemplateMessageFolder extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_template_message_folder';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'desc',
    ];

}
