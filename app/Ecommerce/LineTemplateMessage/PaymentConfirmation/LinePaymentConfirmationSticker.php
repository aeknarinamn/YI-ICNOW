<?php

namespace YellowProject\Ecommerce\LineTemplateMessage\PaymentConfirmation;

use Illuminate\Database\Eloquent\Model;

class LinePaymentConfirmationSticker extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_line_temp_payment_confirmation_sticker';

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
