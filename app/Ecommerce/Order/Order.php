<?php

namespace YellowProject\Ecommerce\Order;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Customer\Customer;
use YellowProject\Ecommerce\Order\OrderProduct;
use YellowProject\Ecommerce\Order\OrderPayment;
use YellowProject\Ecommerce\Order\OrderTracking;
use YellowProject\Ecommerce\Order\OrderAdminHistory;
use YellowProject\Ecommerce\Order\OrderConfirmation;
use YellowProject\Ecommerce\Order\OrderConfirmationPaymentReadyToShip;
use YellowProject\Ecommerce\Order\OrderShippingConfirmation;

class Order extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_order';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_customer_id',
    	'order_id',
        'order_date',
		'order_status',
		'payment_information',
		'sub_total',
		'tax',
		'shipping_cost',
		'grand_total',
		'coupon_code_discount',
		'coupon_code_discount_amount',
		'total_paid',
		'total_refunded',
        'total_due',
        'total_reward_point',
        'order_reward_point',
        'ecommerce_shipping_address_id',
        'operator_shipping_date',
        'operator_shipping_time',
        'dt_shipping_date',
        'dt_id',
        'send_message_48_hours',
        'send_message_72_hours',
		'dt_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class,'ecommerce_customer_id','id');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class,'order_id','id');
    }

    public function orderPayment()
    {
        return $this->hasOne(OrderPayment::class,'order_id','id');
    }

    public function orderTracking()
    {
        return $this->hasOne(OrderTracking::class,'order_id','id');
    }

    public function orderAdminHistories()
    {
        return $this->hasMany(OrderAdminHistory::class,'order_id','id');
    }

    public function orderConfirmation()
    {
        return $this->hasOne(OrderConfirmation::class,'order_id','id');
    }

    public function orderConfirmationPaymentReadyToShip()
    {
        return $this->hasOne(OrderConfirmationPaymentReadyToShip::class,'order_id','id');
    }

    public function orderShippingConfirmation()
    {
        return $this->hasOne(OrderShippingConfirmation::class,'order_id','id');
    }

    public static function genOderId()
    {
        $prefixOrder = "UFS";
        $countOrder = Order::all()->count();
        $countOrderAdding = $countOrder+1;
        $orderWithPad = str_pad($countOrderAdding, 6, '0', STR_PAD_LEFT);
        $orderId = $prefixOrder.$orderWithPad;
        return $orderId;
    }
}
