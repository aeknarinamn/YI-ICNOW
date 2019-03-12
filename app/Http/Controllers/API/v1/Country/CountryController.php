<?php

namespace YellowProject\Http\Controllers\API\v1\Country;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\Country\Province;
use YellowProject\Country\District;
use YellowProject\Country\SubDistrict;

class CountryController extends Controller
{
    public function getProvince()
    {
    	$provinces = Province::all();

    	return response()->json([
            'datas' => $provinces,
        ]);
    }

    public function getAllDistrict(Request $request)
    {
    	$districts = District::all();
    	
    	return response()->json([
            'datas' => $districts,
        ]);
    }

    public function getAllSubDistrict(Request $request)
    {
    	$subDistricts = SubDistrict::all();
    	
    	return response()->json([
            'datas' => $subDistricts,
        ]);
    }

    public function getProvinceDistrict(Request $request)
    {
    	// $provinceId = $request->province_id;ห
        $provinceName = $request->province_name;
        $province = Province::where('name',$provinceName)->first();
        if($province){
            $districts = District::where('province_id',$province->id)->get();
        }else{
            $districts = collect();
        }
    	
    	return response()->json([
            'datas' => $districts,
        ]);
    }

    public function getDistrictSubDistrict(Request $request)
    {
    	// $districtId = $request->district_id;
        $districtName = $request->district_name;
        $district = District::where('name',$districtName)->first();
    	$subDistricts = SubDistrict::where('district_id',$district->id)->get();
    	
    	return response()->json([
            'datas' => $subDistricts,
        ]);
    }
}
