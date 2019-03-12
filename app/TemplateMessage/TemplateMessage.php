<?php

namespace YellowProject\TemplateMessage;

use Illuminate\Database\Eloquent\Model;
use YellowProject\TemplateMessage\TemplateMessageColumn;
use YellowProject\TemplateMessage\TemplateMessageFolder;

class TemplateMessage extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_template_message';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'alt_text',
		'folder_id',
		'type',
    ];

    public function templateMessageColumns()
    {
        return $this->hasMany(TemplateMessageColumn::class,'template_message_id','id');
    }

    public function folder()
    {
        return $this->belongsTo(TemplateMessageFolder::class,'folder_id','id');
    }
}
