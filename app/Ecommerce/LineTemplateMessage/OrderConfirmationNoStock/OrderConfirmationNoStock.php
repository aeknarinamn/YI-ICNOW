<?php

namespace YellowProject\Ecommerce\LineTemplateMessage\OrderConfirmationNoStock;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\LineTemplateMessage\OrderConfirmationNoStock\OrderConfirmationNoStockItem as LineItem;

class OrderConfirmationNoStock extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_line_temp_order_confirmation_no_stock';

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

        static::deleting(function($orderConfirmationNoStock) {
            $orderConfirmationNoStock->lineItems()->delete();
        });
    }

    public static function checkDuplicate($channelId)
    {
        $item = OrderConfirmationNoStock::where('title',$channelId)->first();
        if (is_null($item)) {
            return false;
        } else {
            return true;
        }
    }

    public function lineItems()
    {
        return $this->hasMany(LineItem::class,'ecommerce_line_temp_order_confirmation_no_stock_id','id');
    }
}
