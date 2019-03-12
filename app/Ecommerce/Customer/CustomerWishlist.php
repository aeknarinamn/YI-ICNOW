<?php

namespace YellowProject\Ecommerce\Customer;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Customer\Customer;
use YellowProject\Ecommerce\Product;

class CustomerWishlist extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_customer_wishlists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_customer_id',
    	'ecommerce_product_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class,'ecommerce_customer_id','id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'ecommerce_product_id','id');
    }
}
