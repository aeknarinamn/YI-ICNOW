<?php

namespace YellowProject\Ecommerce\Log;

use Illuminate\Database\Eloquent\Model;

class LogSession extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_log_session';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'line_user_id',
    	'ecommerce_customer_id',
    	'order_id',
    	'is_product_views',
        'is_add_to_cart',
        'is_check_out',
        'is_transaction',
        'is_active',
    ];
}
