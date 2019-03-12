<?php

namespace YellowProject\Ecommerce\LineTemplateMessage\CustomerConfirmPayment;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\LineTemplateMessage\CustomerConfirmPayment\LineCustomerConfirmPaymentItem as LineItem;

class LineCustomerConfirmPayment extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_line_temp_after_customer_payment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'active',
		'alt_text',
    ];


    //Event Listener
    protected static function boot() 
    {
        parent::boot();

       	static::deleting(function($lineCustomerConfirmPayment) {
            $lineCustomerConfirmPayment->lineItems()->delete();
        });
    }

    public static function checkDuplicate($channelId)
    {
        $item = LineCustomerConfirmPayment::where('title',$channelId)->first();
        if (is_null($item)) {
            return false;
        } else {
            return true;
        }
    }

    public function lineItems()
    {
        return $this->hasMany(LineItem::class,'ecommerce_line_temp_after_customer_payment_id','id');
    }
}
