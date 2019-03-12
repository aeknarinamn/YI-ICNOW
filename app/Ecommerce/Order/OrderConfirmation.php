<?php

namespace YellowProject\Ecommerce\Order;

use Illuminate\Database\Eloquent\Model;

class OrderConfirmation extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_order_confirmation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'order_id',
        'product_avaliable_status',
    	'product_avaliable_date',
    	'email_to_customer_template_id',
    	'email_to_dt_template_id',
    	'email_to_admin_template_id',
    	'line_to_customer_template_id',
    	'line_to_dt_template_id',
    	'line_to_admin_template_id',
    ];

}
