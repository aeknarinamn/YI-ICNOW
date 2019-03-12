<?php

namespace YellowProject\Ecommerce\LineTemplateMessage\CustomerPaymentOverdue48;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\LineTemplateMessage\CustomerPaymentOverdue48\CustomerPaymentOverdue48 as Line;
use YellowProject\Ecommerce\LineTemplateMessage\CustomerPaymentOverdue48\CustomerPaymentOverdue48Message as LineMessage;
use YellowProject\Ecommerce\LineTemplateMessage\CustomerPaymentOverdue48\CustomerPaymentOverdue48Sticker as LineSticker;
use YellowProject\LineMessageType;

class CustomerPaymentOverdue48Item extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_line_temp_customer_overdue_payment_48';
    protected $appends = ['show_message'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ecommerce_line_temp_customer_overdue_payment_48_id',
		'message_type_id',
		'seq_no',
		'message_id',
        'sticker_id',
        'richmessage_id',
    ];

    protected static function boot() 
    {
        parent::boot();

       static::deleting(function($customerPaymentOverdue48Item) {
            $customerPaymentOverdue48Item->message()->delete();
            $customerPaymentOverdue48Item->message()->sticker();
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
        return $this->belongsTo(Line::class, 'ecommerce_line_temp_customer_overdue_payment_48_id', 'id');
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
