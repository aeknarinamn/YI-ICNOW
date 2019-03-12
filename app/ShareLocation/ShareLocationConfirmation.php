<?php

namespace YellowProject\ShareLocation;

use Illuminate\Database\Eloquent\Model;
use YellowProject\ShareLocation\ShareLocationConfirmationItem;
use YellowProject\ConfirmationSetting\ConfirmationSettingShareLocation;
use Carbon\Carbon;

class ShareLocationConfirmation extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_share_location_confirmation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'line_user_id',
        'start_time',
        'end_time',
    ];

    public function shareLocationConfimationItems()
    {
        return $this->hasMany(ShareLocationConfirmationItem::class,'share_location_confirmation_id','id')->where('is_active',0);
    }

    public static function createDataConfirmationShareLocation($shareLocationItems,$lineUserProfile)
    {
        // $confirmationSettingShareLocation = ConfirmationSettingShareLocation::first();
        $checkConfirmation = ShareLocationConfirmation::where('line_user_id',$lineUserProfile->id)->where('end_time',null);

        if($checkConfirmation->count() > 0){
            $checkConfirmation->update([
                'end_time' => Carbon::now(),
            ]);
        }

        $confirmation = ShareLocationConfirmation::create([
            'line_user_id' => $lineUserProfile->id,
            'start_time' => Carbon::now(),
        ]);

        foreach ($shareLocationItems as $key => $shareLocationItem) {
            if($key < 5){
                $isActive = 1;
            }else{
                $isActive = 0;
            }
            ShareLocationConfirmationItem::create([
                'share_location_confirmation_id' => $confirmation->id,
                'share_location_item_id' => $shareLocationItem->id,
                'distance' => $shareLocationItem->distance,
                'is_active' => $isActive,
            ]);
        }
    }

    public static function checkConfirmation($text,$lineUserProfile)
    {
        $confirmationSettingShareLocation = ConfirmationSettingShareLocation::first();
    	$isConfirmation = 0;
    	$lineUserProfileId = $lineUserProfile->id;
    	$shareLocationConfirmation = ShareLocationConfirmation::where('line_user_id',$lineUserProfileId)->where('end_time',null)->first();
    	if($shareLocationConfirmation != null){
    		if($text == $confirmationSettingShareLocation->confimation_yes){
    			$isConfirmation = 1;
    		}else{
    			$shareLocationConfirmation->update([
    				'end_time' => Carbon::now(),
    			]);
    		}
    	}

    	return $isConfirmation;
    }
}
