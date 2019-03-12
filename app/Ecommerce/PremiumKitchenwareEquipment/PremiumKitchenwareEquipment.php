<?php

namespace YellowProject\Ecommerce\PremiumKitchenwareEquipment;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\PremiumKitchenwareEquipment\PremiumKitchenwareEquipmentVideo;
use YellowProject\Ecommerce\PremiumKitchenwareEquipment\PremiumKitchenwareEquipmentPdf;
use YellowProject\Ecommerce\PremiumKitchenwareEquipment\PremiumKitchenwareEquipmentUser;

class PremiumKitchenwareEquipment extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_premium_kitchenware_or_equipment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'course_name',
    	'desc',
        'avaliable_strat_date',
		'avaliable_end_date',
		'hours_duration',
		'ep',
		'status',
    ];

    public function videoFiles()
    {
        return $this->hasMany(PremiumKitchenwareEquipmentVideo::class,'premium_kitchenware_id','id');
    }

    public function pdfFiles()
    {
        return $this->hasMany(PremiumKitchenwareEquipmentPdf::class,'premium_kitchenware_id','id');
    }

    public function customers()
    {
        return $this->hasMany(PremiumKitchenwareEquipmentUser::class,'premium_kitchenware_id','id');
    }
}
