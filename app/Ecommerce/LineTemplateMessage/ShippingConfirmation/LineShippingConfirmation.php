<?php

namespace YellowProject\Ecommerce\LineTemplateMessage\ShippingConfirmation;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\LineTemplateMessage\ShippingConfirmation\LineShippingConfirmationItem as LineItem;

class LineShippingConfirmation extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_line_temp_shipping_confirmation';

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

       	static::deleting(function($lineShippingConfirmation) {
            $lineShippingConfirmation->lineItems()->delete();
        });
    }

    public static function checkDuplicate($channelId)
    {
        $item = LineShippingConfirmation::where('title',$channelId)->first();
        if (is_null($item)) {
            return false;
        } else {
            return true;
        }
    }

    public function lineItems()
    {
        return $this->hasMany(LineItem::class,'ecommerce_line_temp_shipping_confirmation_id','id');
    }
}
