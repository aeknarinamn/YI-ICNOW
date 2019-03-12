<?php

namespace YellowProject\Ecommerce\LineTemplateMessage\OrderConfirmation;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\LineTemplateMessage\OrderConfirmation\LineOrderConfirmationItem as LineItem;

class LineOrderConfirmation extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_line_temp_order_confirmation';

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

       	static::deleting(function($lineOrderConfirmation) {
            $lineOrderConfirmation->lineItems()->delete();
        });
    }

    public static function checkDuplicate($channelId)
    {
        $item = LineOrderConfirmation::where('title',$channelId)->first();
        if (is_null($item)) {
            return false;
        } else {
            return true;
        }
    }

    public function lineItems()
    {
        return $this->hasMany(LineItem::class,'ecommerce_line_temp_order_confirmation_id','id');
    }
}
