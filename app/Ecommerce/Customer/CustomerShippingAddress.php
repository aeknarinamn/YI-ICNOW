<?php

namespace YellowProject\Ecommerce\Customer;

use Illuminate\Database\Eloquent\Model;

class CustomerShippingAddress extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_customer_shipping_address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_customer_id',
        'name',
    	'last_name',
        'company_name',
		'address',
		'district',
		'sub_district',
		'province',
		'post_code',
        'tel',
        'tax_id',
        'dt_id',
        'store_name',
		'store_type',
        'latitude',
        'longitude',
    ];

}
