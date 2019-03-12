<?php

namespace YellowProject\RichmessageV2;

use Illuminate\Database\Eloquent\Model;
use YellowProject\RichmessageV2\RichmessageArea;

class Richmessage extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_rich_message_v2';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'alt_text',
		'original_photo_path',
		'height',
		'width',
		'rich_message_url',
    ];

    public function areas()
    {
        return $this->hasMany(RichmessageArea::class,'rich_message_id','id');
    }
}
