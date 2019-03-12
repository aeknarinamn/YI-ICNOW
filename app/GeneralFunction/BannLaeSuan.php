<?php

namespace YellowProject\GeneralFunction;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Field;
use Carbon\Carbon;

class BannLaeSuan extends Model
{
    public static function convertData($datas,$lineUserProfile)
    {
        // dd($datas);
        $dataSets = [];
        $dataSets['lineuserid'] = $lineUserProfile->id;
        $dataSets['mid'] = $lineUserProfile->mid;
        $dataSets['created'] = Carbon::now()->format('Y-m-d').'T'.Carbon::now()->format('H:i:s');
        // dd($datas);
        foreach ($datas as $fieldId => $fieldValue) {
            $field = Field::find($fieldId);
            if($field->name == 'Name_Baan_Lae_Suan'){
                $dataSets['name'] = $fieldValue;
            }else if($field->name == 'Last_Name_Baan_Lae_Suan'){
                $dataSets['surname'] = $fieldValue;
            }else if($field->name == 'Email_Baan_Lae_Suan'){
                $dataSets['email'] = $fieldValue;
            }else if($field->name == 'Mobile_Baan_Lae_Suan'){
                $dataSets['mobile'] = $fieldValue;
            }else if($field->name == 'Month_Create_Baan_Lae_Suan'){
                $dataSets['homeplanmonth'] = self::convertMonth($fieldValue);
            }else if($field->name == 'Home_Type_Baan_Lae_Suan'){
                $dataSets['hometype'] = self::convertHomeType($fieldValue);
            }else if($field->name == 'Year_Create_Baan_Lae_Suan'){
                $dataSets['homeplanyear'] = self::convertYear($fieldValue);
            }
        }

        if($dataSets['homeplanmonth'] == 0 && $dataSets['homeplanyear'] == 0){
                $dataSets['homeplan'] = 'ยังไม่มีแผนในการก่อสร้าง';
        }else{
            $dataSets['homeplan'] = 'แผนการก่อสร้าง';
        }

        return $dataSets;
    }

    public static function convertMonth($fieldValue)
    {
    	$monthValue = "";
		if($fieldValue == 'ม.ค.'){

		}else if($fieldValue == 'ก.พ.'){
			$monthValue = 1;
		}else if($fieldValue == 'ก.พ.'){
			$monthValue = 2;
		}else if($fieldValue == 'มี.ค.'){
			$monthValue = 3;
		}else if($fieldValue == 'เม.ษ.'){
			$monthValue = 4;
		}else if($fieldValue == 'พ.ค.'){
			$monthValue = 5;
		}else if($fieldValue == 'มิ.ย.'){
			$monthValue = 6;
		}else if($fieldValue == 'ก.ค.'){
			$monthValue = 7;
		}else if($fieldValue == 'ส.ค.'){
			$monthValue = 8;
		}else if($fieldValue == 'ก.ย.'){
			$monthValue = 9;
		}else if($fieldValue == 'ต.ค.'){
			$monthValue = 10;
		}else if($fieldValue == 'พ.ย.'){
			$monthValue = 11;
		}else if($fieldValue == 'ธ.ค.'){
			$monthValue = 12;
		}else{
			$monthValue = 0;
		}

    	return $monthValue;
    }

    public static function convertYear($fieldValue)
    {
    	$yearValue = "";
		if($fieldValue == '2560'){
			$yearValue = "2017";
		}else if($fieldValue == '2561'){
			$yearValue = "2018";
		}else if($fieldValue == '2562 ขึ้นไป'){
			$yearValue = "2019";
		}else{
			$yearValue = 0;
		}

    	return $yearValue;
    }

    public static function convertHomeType($fieldValue)
    {
    	$homeType = "";
    	if($fieldValue == '-- Select --'){
			$homeType = 'ยังไม่แน่ใจ';
		}else{
			$homeType = $fieldValue;
		}

    	return $homeType;
    }

    public static function getAllRefTracking()
    {
        $refs = ['Truss','BeautifulBathroomCOTTO','RainGutter','Pipe','CottoBeautifulBathroom','CottoSection3','CottoSection2','Cotto','SCGExpress','ActiveAIRflow','SCGStayCool','EldercarePromotion2017','EldercareSensoryGarden','EldercareStairLift','EldercareSoftDeck','EldercareShockAbsorptionFloor','EldercareInteriorSlidingDoor','EldercareIntelligentNightLight','EldercareSection2','Eldercare','StayCool','Transluc','ACOUSIC','Shinkolite','LandscapeSection2','Landscape','DeckSystem','Cementboard','SmartwoodSection4','SmartwoodSection3','SmartwoodSection2','Smartwood','Smartboard','RoofSection3','RoofSection2','Roof','VINYL','Windsor'];

        return $refs;
    }
}
