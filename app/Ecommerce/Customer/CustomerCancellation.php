<?php

namespace YellowProject\Ecommerce\Customer;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Customer\Customer;
use YellowProject\Ecommerce\Order\Order;

class CustomerCancellation extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_customer_cancellations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_customer_id',
    	'order_id',
        'status',
		'cancle_date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class,'ecommerce_customer_id','id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }
}
