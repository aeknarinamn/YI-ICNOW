<?php

namespace YellowProject\Ecommerce\LineTemplateMessage\CustomerConfirmPayment;

use Illuminate\Database\Eloquent\Model;

class LineCustomerConfirmPaymentSticker extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_line_temp_after_customer_payment_sticker';

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

