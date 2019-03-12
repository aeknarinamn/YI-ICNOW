<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\CarouselConfirmationItem;
use YellowProject\ConfirmationSetting\ConfirmationSettingCarousel;
use Carbon\Carbon;

class CarouselConfirmation extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_carousel_confirmation';

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
        return $this->hasMany(CarouselConfirmationItem::class,'carousel_confirmation_id','id')->where('is_active',0);
    }

    public static function createDataConfirmation($carouselKeywords,$lineUserProfile)
    {
        // $confirmationSettingCarousel = ConfirmationSettingCarousel::first();
        $checkConfirmation = CarouselConfirmation::where('line_user_id',$lineUserProfile->id)->where('end_time',null);

        if($checkConfirmation->count() > 0){
            $checkConfirmation->update([
                'end_time' => Carbon::now(),
            ]);
        }
        
        $confirmation = CarouselConfirmation::create([
            'line_user_id' => $lineUserProfile->id,
            'start_time' => Carbon::now(),
        ]);

        foreach ($carouselKeywords as $key => $carouselKeyword) {
            if($key < 5){
                $isActive = 1;
            }else{
                $isActive = 0;
            }
            CarouselConfirmationItem::create([
                'carousel_confirmation_id' => $confirmation->id,
                'carousel_item_id' => $carouselKeyword->carouselItem->id,
                'is_active' => $isActive,
            ]);
        }
    }

    public static function createDataConfirmationShareLocation($carouselItems,$lineUserProfile)
    {
        $confirmation = CarouselConfirmation::create([
            'line_user_id' => $lineUserProfile->id,
            'start_time' => Carbon::now(),
        ]);

        foreach ($carouselItems as $key => $carouselItem) {
            if($key < 5){
                $isActive = 1;
            }else{
                $isActive = 0;
            }
            CarouselConfirmationItem::create([
                'carousel_confirmation_id' => $confirmation->id,
                'carousel_item_id' => $carouselItem->id,
                'is_active' => $isActive,
            ]);
        }
    }

    public static function checkConfirmation($text,$lineUserProfile)
    {
        $confirmationSettingCarousel = ConfirmationSettingCarousel::first();
    	$isConfirmation = 0;
    	$lineUserProfileId = $lineUserProfile->id;
    	$carouselConfirmation = CarouselConfirmation::where('line_user_id',$lineUserProfileId)->where('end_time',null)->first();
    	if($carouselConfirmation != null){
    		if($text == $confirmationSettingCarousel->confimation_yes){
    			$isConfirmation = 1;
    		}elseif($text == $confirmationSettingCarousel->confimation_no){
                 $carouselConfirmation->update([
                     'end_time' => Carbon::now(),
                 ]);
            }
      //       else{
    		// 	$carouselConfirmation->update([
    		// 		'end_time' => Carbon::now(),
    		// 	]);
    		// }
    	}

    	return $isConfirmation;
    }
}
