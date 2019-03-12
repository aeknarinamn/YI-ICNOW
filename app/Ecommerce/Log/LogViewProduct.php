<?php

namespace YellowProject\Ecommerce\Log;

use Illuminate\Database\Eloquent\Model;

class LogViewProduct extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_log_view_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_customer_id',
    	'product_id',
    ];
}
