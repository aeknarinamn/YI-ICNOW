<?php

namespace YellowProject\ICNOW\OrderCustomer;

use Illuminate\Database\Eloquent\Model;

class CustomerShippingAddress extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_icnow_customer_shipping_address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'line_user_id',
        'first_name',
        'last_name',
        'address',
        'sub_district',
        'district',
        'province',
        'post_code',
        'phone_number',
        'lattitude',
        'longtitude',
        'is_active',
        'remark',
    ];
}
