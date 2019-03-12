<?php

namespace YellowProject\Ecommerce\LineTemplateMessage\LineTemplateMessageAuto;

use Illuminate\Database\Eloquent\Model;

class OrderConfirmationNoStock extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_line_temp_auto_order_confirmation_no_stock';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'line_to_customer_template_id',
        'line_to_dt_template_id',
		'line_to_admin_template_id',
    ];
}
