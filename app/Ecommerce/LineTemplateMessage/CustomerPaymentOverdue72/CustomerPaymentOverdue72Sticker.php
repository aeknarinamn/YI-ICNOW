<?php

namespace YellowProject\Ecommerce\LineTemplateMessage\CustomerPaymentOverdue72;

use Illuminate\Database\Eloquent\Model;

class CustomerPaymentOverdue72Sticker extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_line_temp_customer_overdue_payment_72_sticker';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
		'packageId',
		'stickerId',
        'display',//
    ];
}
