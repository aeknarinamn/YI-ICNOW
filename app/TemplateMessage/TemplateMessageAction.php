<?php

namespace YellowProject\TemplateMessage;

use Illuminate\Database\Eloquent\Model;

class TemplateMessageAction extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_template_message_action';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_message_column_id',
		'action',
		'label',
		'value',
		'seq',
    ];

}
