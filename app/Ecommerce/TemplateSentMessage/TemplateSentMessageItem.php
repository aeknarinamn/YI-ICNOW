<?php

namespace YellowProject\Ecommerce\TemplateSentMessage;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\TemplateSentMessage\TemplateSentMessageMain;
use YellowProject\Ecommerce\TemplateSentMessage\TemplateSentMessage;
use YellowProject\Ecommerce\TemplateSentMessage\TemplateSentMessageSticker;
use YellowProject\LineMessageType;
use YellowProject\RichMessage;

class TemplateSentMessageItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_template_sent_message';
    protected $appends = ['show_message'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dim_ecommerce_template_sent_message_id',
		'message_type_id',
		'seq_no',
		'message_id',
        'auto_reply_message_id',
        'auto_reply_sticker_id',
        'auto_reply_richmessage_id',
    ];

    public function getShowMessageAttribute()
    {
        $message = null;
        switch ( $this->messageType->type ) {
            case 'text':
                $message  = [
                    'playload' => $this->message->message,
                    'display' => $this->message->display,
                ];

                break;
            case 'sticker':
                $message  = [
                    'package_id' => $this->sticker->packageId,
                    'stricker_id' => $this->sticker->stickerId,
                    'display' => $this->sticker->display,
                ];

                break;
            case 'imagemap':
                $message  = [
                    'auto_reply_richmessage_id' => $this->auto_reply_richmessage_id,
                ];

                break;
        }
        return $message;
    }



    public function autoReplyKeyWord()
    {
        return $this->belongsTo(TemplateSentMessageMain::class, 'dim_approval_bot_auto_reply_id', 'id');
    }

    public function message()
    {
        return $this->belongsTo(TemplateSentMessage::class, 'auto_reply_message_id', 'id');
    }

    public function sticker()
    {
        return $this->belongsTo(TemplateSentMessageSticker::class, 'auto_reply_sticker_id', 'id');
    }

    public function messageType()
    {
        return $this->belongsTo(LineMessageType::class, 'message_type_id', 'id');
    }

    public function imageRichmessage()
    {
        return $this->belongsTo(RichMessage::class, 'auto_reply_richmessage_id', 'id');
    }

    public function setMessageTypeIdAttribute($value)
    {

        $lineMessageType = LineMessageType::where('type', $value)->first();
        if(is_null($lineMessageType)) return false;

        $this->attributes['message_type_id'] = $lineMessageType->id;
    }
}
