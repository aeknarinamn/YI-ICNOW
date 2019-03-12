<?php

namespace YellowProject\ICNOW\OrderCustomer;

use Illuminate\Database\Eloquent\Model;

class OrderCustomerHistory extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_icnow_customer_order_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'order_no',
        'user_id',
        'email',
        'username',
        'from_status',
        'to_status',
    ];
}
