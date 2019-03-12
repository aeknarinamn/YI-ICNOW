<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\CoroselConfirmationItem;
use Carbon\Carbon;

class CoroselConfirmation extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_corosel_confirmation';

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

    public function confirmationItems()
    {
        return $this->hasMany(CoroselConfirmationItem::class,'corosel_confirmation_id','id')->where('is_active',0);
    }

    public static function createDataConfirmation($locationKeywords,$lineUserProfile)
    {
    	$confirmation = CoroselConfirmation::create([
    		'line_user_id' => $lineUserProfile->id,
    		'start_time' => Carbon::now(),
    	]);

    	foreach ($locationKeywords as $key => $locationKeyword) {
    		if($key < 5){
    			$isActive = 1;
    		}else{
    			$isActive = 0;
    		}
    		CoroselConfirmationItem::create([
    			'corosel_confirmation_id' => $confirmation->id,
    			'location_item_id' => $locationKeyword->locationItem->id,
    			'is_active' => $isActive,
    		]);
    	}
    }

    public static function createDataConfirmationShareLocation($locationKeywords,$lineUserProfile)
    {
    	$confirmation = CoroselConfirmation::create([
    		'line_user_id' => $lineUserProfile->id,
    		'start_time' => Carbon::now(),
    	]);

    	foreach ($locationKeywords as $key => $locationItem) {
    		if($key < 5){
    			$isActive = 1;
    		}else{
    			$isActive = 0;
    		}
    		CoroselConfirmationItem::create([
    			'corosel_confirmation_id' => $confirmation->id,
    			'location_item_id' => $locationItem->id,
    			'is_active' => $isActive,
    		]);
    	}
    }

    public static function checkConfirmation($text,$lineUserProfile)
    {
    	$isConfirmation = 0;
    	$lineUserProfileId = $lineUserProfile->id;
    	$coroselConfirmation = CoroselConfirmation::where('line_user_id',$lineUserProfileId)->where('end_time',null)->first();
    	if($coroselConfirmation != null){
    		if($text == 'ใช่'){
    			$isConfirmation = 1;
    		}else{
    			$coroselConfirmation->update([
    				'end_time' => Carbon::now(),
    			]);
    		}
    	}

    	return $isConfirmation;
    }
}
