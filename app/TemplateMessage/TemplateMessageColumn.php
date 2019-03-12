<?php

namespace YellowProject\TemplateMessage;

use Illuminate\Database\Eloquent\Model;
use YellowProject\TemplateMessage\TemplateMessageAction;

class TemplateMessageColumn extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_template_message_column';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_message_id',
		'title',
		'desc',
		'img_url',
		'seq',
    ];

    public function templateMessageActions()
    {
        return $this->hasMany(TemplateMessageAction::class,'template_message_column_id','id');
    }
}
