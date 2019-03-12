<?php

namespace YellowProject\Ecommerce\PremiumKitchenwareEquipment;

use Illuminate\Database\Eloquent\Model;

class PremiumKitchenwareEquipmentFile extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_premium_kitchenware_or_equipment_file';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'link_path',
    	'type',
    ];

    public static function checkFolderPremiumKitchenwareEquipment()
    {
        if (!\File::isDirectory('ecommerce')){
            self::createFolder('ecommerce');
        }else{
            if (!\File::isDirectory('ecommerce/premium_kitchenware')){
                self::createFolder('ecommerce/premium_kitchenware');
            }
        }
    }

    public static function createFolder($path_save_image)
    {
        $result = \File::makeDirectory($path_save_image, 0775, true);
        return $result;
    }

}
