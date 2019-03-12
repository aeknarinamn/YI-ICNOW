<?php

namespace YellowProject\Ecommerce\LineTemplateMessage\PaymentConfirmation;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\LineTemplateMessage\PaymentConfirmation\LinePaymentConfirmation as Line;
use YellowProject\Ecommerce\LineTemplateMessage\PaymentConfirmation\LinePaymentConfirmationMessage as LineMessage;
use YellowProject\Ecommerce\LineTemplateMessage\PaymentConfirmation\LinePaymentConfirmationSticker as LineSticker;
use YellowProject\LineMessageType;

class LinePaymentConfirmationItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_line_temp_payment_confirmation';
    protected $appends = ['show_message'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ecommerce_line_temp_payment_confirmation_id',
		'message_type_id',
		'seq_no',
		'message_id',
        'sticker_id',
        'richmessage_id',
    ];

    protected static function boot() 
    {
        parent::boot();

       static::deleting(function($linePaymentConfirmationItem) {
            $linePaymentConfirmationItem->message()->delete();
            $linePaymentConfirmationItem->message()->sticker();
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
                    'auto_reply_richmessage_id' => $this->richmessage_id,
                ];

                break;
        }
        
        return $message;
    }



    public function line()
    {
        return $this->belongsTo(Line::class, 'ecommerce_line_temp_payment_confirmation_id', 'id');
    }


    public function message()
    {
        return $this->belongsTo(LineMessage::class, 'message_id', 'id');
    }

    public function sticker()
    {
        return $this->belongsTo(LineSticker::class, 'sticker_id', 'id');
    }

    public function messageType()
    {
        return $this->belongsTo(LineMessageType::class, 'message_type_id', 'id');
    }
}
