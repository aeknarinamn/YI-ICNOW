<?php

namespace YellowProject\Ecommerce\Customer;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Customer\CustomerShippingAddress;
use YellowProject\Ecommerce\Customer\CustomerTaxBillingAddress;
use YellowProject\Ecommerce\Customer\CustomerCancellation;
use YellowProject\Ecommerce\Customer\CustomerWishlist;
use YellowProject\Ecommerce\Customer\CustomerCoupon;
use YellowProject\Ecommerce\Customer\CustomerImage;
use YellowProject\Ecommerce\Order\Order;
use YellowProject\Ecommerce\PremiumKitchenwareEquipment\PremiumKitchenwareEquipmentUser;
use YellowProject\Ecommerce\ExclusiveOnline\ExclusiveOnlineUser;
use YellowProject\LineUserProfile;

class Customer extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_customer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_code',
    	'line_user_id',
    	'first_name',
        'last_name',
		'email',
		'city',
		'phone_number',
        'reward_point',
		'img_id',
        'market_name',
        'market_type',
        'post_code',
        'dt_id',
    ];

    public function customerShippingAddresses()
    {
        return $this->hasMany(CustomerShippingAddress::class,'ecommerce_customer_id','id');
    }

    public function CustomerTaxBillingAddress()
    {
        return $this->hasOne(CustomerTaxBillingAddress::class,'ecommerce_customer_id','id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class,'ecommerce_customer_id','id');
    }

    public function customerWishlists()
    {
        return $this->hasMany(CustomerWishlist::class,'ecommerce_customer_id','id');
    }

    public function customerCancellations()
    {
        return $this->hasMany(CustomerCancellation::class,'ecommerce_customer_id','id');
    }

    public function lineUserProfile()
    {
        return $this->belongsTo(LineUserProfile::class,'line_user_id','id');
    }

    public function customerImage()
    {
        return $this->belongsTo(CustomerImage::class,'img_id','id');
    }

    public function customerCoupons()
    {
        return $this->hasMany(CustomerCoupon::class,'ecommerce_coupon_id','id');
    }

    public function customerRewardKitchenWares()
    {
        return $this->hasMany(PremiumKitchenwareEquipmentUser::class,'ecommerce_customer_id','id')->where('status','Approve');
    }

    public function customerRewardExclusives()
    {
        return $this->hasMany(ExclusiveOnlineUser::class,'ecommerce_customer_id','id')->where('status','Approve');
    }

}
