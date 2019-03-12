<?php

namespace YellowProject\Ecommerce\LineTemplateMessage\CustomerPaymentOverdue48;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\LineTemplateMessage\CustomerPaymentOverdue48\CustomerPaymentOverdue48Item as LineItem;

class CustomerPaymentOverdue48 extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_line_temp_customer_overdue_payment_48';

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

       	static::deleting(function($customerPaymentOverdue48) {
            $customerPaymentOverdue48->lineItems()->delete();
        });
    }

    public static function checkDuplicate($channelId)
    {
        $item = CustomerPaymentOverdue48::where('title',$channelId)->first();
        if (is_null($item)) {
            return false;
        } else {
            return true;
        }
    }

    public function lineItems()
    {
        return $this->hasMany(LineItem::class,'ecommerce_line_temp_customer_overdue_payment_48_id','id');
    }
}
