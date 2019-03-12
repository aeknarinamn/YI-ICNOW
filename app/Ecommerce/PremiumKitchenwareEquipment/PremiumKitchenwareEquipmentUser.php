<?php

namespace YellowProject\Ecommerce\PremiumKitchenwareEquipment;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Customer\Customer;
use YellowProject\Ecommerce\PremiumKitchenwareEquipment\PremiumKitchenwareEquipment;
use YellowProject\Ecommerce\PremiumKitchenwareEquipment\PremiumKitchenwareEquipmentVideo;
use YellowProject\Ecommerce\PremiumKitchenwareEquipment\PremiumKitchenwareEquipmentPdf;

class PremiumKitchenwareEquipmentUser extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_premium_kitchenware_or_equipment_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'premium_kitchenware_id',
    	'ecommerce_customer_id',
    	'status',
    ];

    public function ecommerceCustomer()
    {
        return $this->belongsTo(Customer::class,'ecommerce_customer_id','id');
    }

    public function videoFiles()
    {
        return $this->hasMany(PremiumKitchenwareEquipmentVideo::class,'premium_kitchenware_id','premium_kitchenware_id');
    }

    public function pdfFiles()
    {
        return $this->hasMany(PremiumKitchenwareEquipmentPdf::class,'premium_kitchenware_id','premium_kitchenware_id');
    }

    public function premiumKitchenwareEquipment()
    {
        return $this->belongsTo(PremiumKitchenwareEquipment::class,'premium_kitchenware_id','id');
    }

}
