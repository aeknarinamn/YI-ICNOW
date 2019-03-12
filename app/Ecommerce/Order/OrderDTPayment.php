<?php

namespace YellowProject\Ecommerce\Order;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Order\Order;
use YellowProject\Ecommerce\Customer\Customer;

class OrderDTPayment extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_order_dt_payment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'order_id',
    	'dt_id',
        'ecommerce_customer_id',
		'payment_code',
		'payment_url',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class,'ecommerce_customer_id','id');
    }
}
