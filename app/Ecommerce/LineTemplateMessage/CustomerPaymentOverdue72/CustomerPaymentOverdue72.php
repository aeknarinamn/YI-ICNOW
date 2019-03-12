<?php

namespace YellowProject\Ecommerce\LineTemplateMessage\CustomerPaymentOverdue72;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\LineTemplateMessage\CustomerPaymentOverdue72\CustomerPaymentOverdue72Item as LineItem;

class CustomerPaymentOverdue72 extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_line_temp_customer_overdue_payment_72';

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

       	static::deleting(function($customerPaymentOverdue72) {
            $customerPaymentOverdue72->lineItems()->delete();
        });
    }

    public static function checkDuplicate($channelId)
    {
        $item = CustomerPaymentOverdue72::where('title',$channelId)->first();
        if (is_null($item)) {
            return false;
        } else {
            return true;
        }
    }

    public function lineItems()
    {
        return $this->hasMany(LineItem::class,'ecommerce_line_temp_customer_overdue_payment_72_id','id');
    }
}
