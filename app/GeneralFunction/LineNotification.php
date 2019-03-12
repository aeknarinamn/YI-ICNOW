<?php

namespace YellowProject\GeneralFunction;

use Illuminate\Database\Eloquent\Model;

class LineNotification extends Model
{
    public static function sentMessageLineNoti($message)
    {
    	$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://notify-api.line.me/api/notify",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "message=".$message,
		  CURLOPT_HTTPHEADER => array(
		    "authorization: Bearer r1aEpJ6oI10OmCsNYGYbuJh0HfUMQoKCfK3d8M0j15O",
		    "cache-control: no-cache",
		    "content-type: application/x-www-form-urlencoded",
		    "postman-token: b95aa35c-d983-d868-6d28-1fa6c0ed2924"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  // echo "cURL Error #:" . $err;
		 	\Log::debug("cURL Error #:" . $err);
		} 

		// return Response::json([
  //               'success' => true
  //       ], 200);
    }
}
