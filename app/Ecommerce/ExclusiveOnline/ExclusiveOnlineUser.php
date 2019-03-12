<?php

namespace YellowProject\Ecommerce\ExclusiveOnline;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Customer\Customer;
use YellowProject\Ecommerce\ExclusiveOnline\ExclusiveOnline;
use YellowProject\Ecommerce\ExclusiveOnline\ExclusiveOnlineVideo;
use YellowProject\Ecommerce\ExclusiveOnline\ExclusiveOnlinePdf;

class ExclusiveOnlineUser extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_exclusive_online_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'exclusive_online_id',
    	'ecommerce_customer_id',
    	'status',
    ];

    public function ecommerceCustomer()
    {
        return $this->belongsTo(Customer::class,'ecommerce_customer_id','id');
    }

    public function videoFiles()
    {
        return $this->hasMany(ExclusiveOnlineVideo::class,'exclusive_online_id','exclusive_online_id');
    }

    public function pdfFiles()
    {
        return $this->hasMany(ExclusiveOnlinePdf::class,'exclusive_online_id','exclusive_online_id');
    }

    public function exclusiveOnline()
    {
        return $this->belongsTo(exclusiveOnline::class,'exclusive_online_id','id');
    }
}
