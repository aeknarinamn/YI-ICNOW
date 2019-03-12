<?php

namespace YellowProject\ICNOW\OrderCustomer;

use Illuminate\Database\Eloquent\Model;

class OrderCustomer extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_icnow_customer_order';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'line_user_id',
        'order_no',
        'shopping_cart_id',
        'address_id',
        'date_of_delivery',
        'time_of_delivery',
        'status',
        'mini_id',
        'mini_code',
        'mini_name',
        'dt_code',
        'cancle_case',
        'cancle_comment',
        'exp_time',
        'accept_delivery_date',
        'finish_delivery_date',
        'customer_submit_shopping_cart_date',
        'customer_submit_shopping_cart_time',
        'customer_submit_order_date',
        'customer_submit_order_time',
        'mini_confirm_delivery_date',
        'mini_confirm_delivery_time',
        'is_rating',
        'rating_1',
        'rating_2',
        'rating_3',
        'rating_4',
        'suggestion',
    ];

    public static function genOrderNumber()
    {
        $count = OrderCustomer::all()->count();
        $pad = str_pad($count+1, 8, '0', STR_PAD_LEFT);
        $docId = "IC"."_".$pad;

        return $docId;
    }
}
