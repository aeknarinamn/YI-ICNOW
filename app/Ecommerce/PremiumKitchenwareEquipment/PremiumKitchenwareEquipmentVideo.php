<?php

namespace YellowProject\Ecommerce\PremiumKitchenwareEquipment;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\PremiumKitchenwareEquipment\PremiumKitchenwareEquipmentFile;

class PremiumKitchenwareEquipmentVideo extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_premium_kitchenware_or_equipment_video';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'premium_kitchenware_id',
        'ecommerce_premium_kitchenware_or_equipment_file_id',
    	'label',
    ];

    public function file()
    {
        return $this->belongsTo(PremiumKitchenwareEquipmentFile::class,'ecommerce_premium_kitchenware_or_equipment_file_id','id');
    }
}
