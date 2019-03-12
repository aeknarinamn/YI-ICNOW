<?php

namespace YellowProject\Ecommerce\TemplateSentMessage;

use Illuminate\Database\Eloquent\Model;

class TemplateSentMessageSticker extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ecommerce_template_sent_message_sticker';

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
