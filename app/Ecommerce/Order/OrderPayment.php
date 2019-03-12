<?php

namespace YellowProject\Ecommerce\Order;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Image\Image;

class OrderPayment extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_order_payment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'order_id',
    	'payment_date_submit',
        'payment_transaction_id',
		'payment_amount',
		'payment_date_refund',
		'payment_refund_transaction_id',
		'payment_refund_status',
		'payment_refund_amount',
        'payment_type',
		'payment_img_id',
    ];

    public function image()
    {
        return $this->belongsTo(Image::class,'payment_img_id','id');
    }

    public static function genPaymentId()
    {
        $prefixPayment = "PA";
        $countPayment = OrderPayment::all()->count();
        $countPaymentAdding = $countPayment+1;
        $paymentWithPad = str_pad($countPaymentAdding, 6, '0', STR_PAD_LEFT);
        $paymentId = $prefixPayment.$paymentWithPad;
        return $paymentId;
    }
}
