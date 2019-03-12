<?php

namespace YellowProject\ICNOW\CoreFunction;

use Illuminate\Database\Eloquent\Model;

class CoreFunction extends Model
{
    public static function getLocationFromGoogle($lat,$long)
    {
    	$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$long."&language=th&key=AIzaSyBB1Rd5CN5S8taXbmNw-_YWGJyJ3CcFZik",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "Cache-Control: no-cache",
		    "Postman-Token: 9668ba7c-de41-4317-af3f-355832efea07"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			\Log::debug("cURL Error #:" . $err);
		  // echo "cURL Error #:" . $err;
		} else {
		  // echo $response;
		}

		return $response;
    }
}
