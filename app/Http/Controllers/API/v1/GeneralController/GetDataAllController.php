<?php

namespace YellowProject\Http\Controllers\API\v1\GeneralController;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\Field;
use YellowProject\LineUserProfile;
use YellowProject\Country\Province;

class GetDataAllController extends Controller
{
    public function getPersonalizeField()
    {
    	$fields = Field::where('is_personalize',1)->get();
    	return response()->json([
            'datas' => $fields,
        ]);
    }

    public function getAllUserID()
    {
    	$lineUserProfileIds = LineUserProfile::all();

    	return response()->json([
            'datas' => $lineUserProfileIds,
        ]);
    }

    public function getProvince()
    {
        $provinces = Province::all();

        return response()->json([
            'datas' => $provinces,
        ]);
    }
}
