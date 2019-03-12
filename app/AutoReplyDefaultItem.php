<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutoReplyDefaultItem extends Model
{
    use SoftDeletes;
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_auto_reply_default';
    protected $appends = ['show_message'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dim_auto_reply_default_id',
		'message_type_id',
		'seq_no',
		'message_id',
        'auto_reply_message_id',
        'auto_reply_sticker_id',
        'auto_reply_richmessage_id',
    ];

    protected static function boot() 
    {
        parent::boot();

       static::deleting(function($autoReplyDefaultItem) {
            $autoReplyDefaultItem->message()->delete();
            $autoReplyDefaultItem->message()->sticker();
        });
    }

    public function setMessageTypeIdAttribute($value)
    {

        $lineMessageType = LineMessageType::where('type', $value)->first();
       
        if(is_null($lineMessageType)) return false;


        $this->attributes['message_type_id'] = $lineMessageType->id;
    }

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



    public function autoReplyDefault()
    {
        return $this->belongsTo(AutoReplyDefault::class, 'dim_auto_reply_default_id', 'id');
    }


    public function message()
    {
        return $this->belongsTo(AutoReplyDefaultMessage::class, 'auto_reply_message_id', 'id');
    }

    public function sticker()
    {
        return $this->belongsTo(AutoReplyDefaultSticker::class, 'auto_reply_sticker_id', 'id');
    }

    public function messageType()
    {
        return $this->belongsTo(LineMessageType::class, 'message_type_id', 'id');
    }
}
