<?php

namespace YellowProject\Ecommerce\Customer;

use Illuminate\Database\Eloquent\Model;

class CustomerReedeemRewardHistory extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_customer_history_reedeem_reward';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_customer_id',
    	'type',
    	'ep',
    ];
}
