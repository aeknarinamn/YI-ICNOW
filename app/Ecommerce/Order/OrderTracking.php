<?php

namespace YellowProject\Ecommerce\Order;

use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_order_tracking';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'order_id',
        'img_id',
    	'tracking_status',
    	'tracking',
    	'check_tracking',
    	'email_to_customer_template_id',
    	'email_to_customer_status',
    	'email_to_dt_template_id',
    	'email_to_dt_status',
    	'email_to_admin_template_id',
    	'email_to_admin_status',
    	'line_to_customer_template_id',
    	'line_to_customer_status',
    	'line_to_dt_template_id',
    	'line_to_dt_status',
    	'line_to_admin_template_id',
    	'line_to_admin_status',
    ];

}
