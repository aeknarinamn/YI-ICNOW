<?php

namespace YellowProject\Ecommerce\Log;

use Illuminate\Database\Eloquent\Model;

class DTConfirmationSendMessage extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_log_send_message_dt_confirmation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'order_id',
    	'ecommerce_customer_id',
    	'dt_id',
        'type',
    ];
}
