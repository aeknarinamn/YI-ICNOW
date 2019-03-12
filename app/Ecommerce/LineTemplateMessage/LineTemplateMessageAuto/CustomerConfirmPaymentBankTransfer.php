<?php

namespace YellowProject\Ecommerce\LineTemplateMessage\LineTemplateMessageAuto;

use Illuminate\Database\Eloquent\Model;

class CustomerConfirmPaymentBankTransfer extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_line_temp_auto_message_cus_conf_pay_bank_trans';

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