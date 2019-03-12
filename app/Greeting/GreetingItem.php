<?php

namespace YellowProject\Greeting;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Greeting\Greeting;
use YellowProject\Greeting\GreetingItemMessage;
use YellowProject\Greeting\GreetingItemSticker;
use YellowProject\LineMessageType;

class GreetingItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_greeting';
    protected $appends = ['show_message'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'greeting_id',
		'message_type_id',
		'seq_no',
		'message_id',
        'greeting_message_id',
        'greeting_sticker_id',
        'greeting_richmessage_id',
    ];

    protected static function boot() 
    {
        parent::boot();

       static::deleting(function($greetingItem) {
            $greetingItem->message()->delete();
            $greetingItem->message()->sticker();
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
                    'auto_reply_richmessage_id' => $this->greeting_richmessage_id,
                ];

                break;
        }
        
        return $message;
    }



    public function greeting()
    {
        return $this->belongsTo(Greeting::class, 'greeting_id', 'id');
    }


    public function message()
    {
        return $this->belongsTo(GreetingItemMessage::class, 'greeting_message_id', 'id');
    }

    public function sticker()
    {
        return $this->belongsTo(GreetingItemSticker::class, 'greeting_sticker_id', 'id');
    }

    public function messageType()
    {
        return $this->belongsTo(LineMessageType::class, 'message_type_id', 'id');
    }
}
