<?php

namespace YellowProject\ICNOW\APIConnection;

use Illuminate\Database\Eloquent\Model;

class MiniConnection extends Model
{
    public static function connectMini($lat,$long)
    {
    	\Log::debug("Lat => ".$lat."Long => ".$long);
    	$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://mbcnextgen-api.net/api/point/mini",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "lat=".$lat."&lng=".$long."&key=MBCNEXTGEN18F108",
		  CURLOPT_HTTPHEADER => array(
		    "Cache-Control: no-cache",
		    "Content-Type: application/x-www-form-urlencoded",
		    "Postman-Token: d4f098d0-01aa-4289-867e-8e2aa24a8840"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			\Log::debug("cURL Error #:" . $err);
		  // echo "cURL Error #:" . $err;
		} else {
			\Log::debug($response);
		  // echo $response;
		}

		return json_decode($response);
    }

    public static function getUserMini()
    {
    	$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://mbcnextgen-api.net/api/list/users",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "key=MBCNEXTGEN18F108",
		  CURLOPT_HTTPHEADER => array(
		    "Cache-Control: no-cache",
		    "Content-Type: application/x-www-form-urlencoded",
		    "Postman-Token: 066216a5-e2a0-457d-a689-d1089cc11c92"
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

		return json_decode($response);
    }
}
