<?php

namespace YellowProject\Ecommerce\Order;

use Illuminate\Database\Eloquent\Model;

class OrderAdminHistory extends Model
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
    	'user_id',
    	'detail',
    ];

}
