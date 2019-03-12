<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

const OAUTH2_AUTH_URL = 'https://access.line.me/dialog/oauth/weblogin';
const OAUTH2_TOKEN_URI = 'https://api.line.me/v2/oauth/accessToken';
const CONNECTION_ESTABLISHED = "HTTP/1.1 200 Connection established\r\n\r\n";

##### End Configuration section ########

##### Check relate Library #####
if (! function_exists('json_decode')) {
	throw new Exception('LINE PHP Client requires JSON extensions');
}

if (! function_exists('curl_init')) {
	throw new Exception('LINE PHP Client requires curl extensions');
}

if (! function_exists('http_build_query')) {
	throw new Exception('LINE PHP Client requires http_build_query()');
}

if (! ini_get('date.timezone') && function_exists('date_default_timezone_set')) {
	date_default_timezone_set('UTC');
}

class LineLogin extends Model
{
    public static function createAuthUrl($clientId,$redirectUri,$stateId){
      $params = array(
      'response_type=code',
      'client_id=' . $clientId,
      'redirect_uri=' . $redirectUri,
      'state=' . $stateId
      );
      $params = implode('&',$params);
      return OAUTH2_AUTH_URL . "?$params";
    }

    public static function parseResponseHeaders($rawHeaders) {
  		$responseHeaders = array();
  		$responseHeaderLines = explode("\r\n", $rawHeaders);
  		foreach ($responseHeaderLines as $headerLine) {
  		  if ($headerLine && strpos($headerLine, ':') !== false) {
  			     list($header, $value) = explode(': ', $headerLine, 2);
  			     $header = strtolower($header);
  			        if (isset($responseHeaders[$header])) {
  			             $responseHeaders[$header] .= "\n" . $value;
  			        } else {
  			             $responseHeaders[$header] = $value;
  	            }
  		  }
  		}
  		return $responseHeaders;
  	}

  	public static function parseHttpResponse($respData, $headerSize) {

        if (stripos($respData, CONNECTION_ESTABLISHED) !== false) {
			       $respData = str_ireplace(CONNECTION_ESTABLISHED, '', $respData);
        }

        if ($headerSize) {
			       $responseBody = substr($respData, $headerSize);
			       $responseHeaders = substr($respData, 0, $headerSize);
        } else {
			       list($responseHeaders, $responseBody) = explode("\r\n\r\n", $respData, 2);
        }

        $responseHeaders = self::parseResponseHeaders($responseHeaders);
        return array($responseHeaders, $responseBody);
    }

    public static function makerequest($authorizationtoken,$clientId,$clientSecret,$redirectUri){

      #### Pre Configuration ####
      $content = file_get_contents('php://input');
      #$events = json_decode($content, true);


      ### Call endpoint v2 to getAccessToken after user authorized

      $postbody = array(
        'grant_type=authorization_code',
        'code=' . $authorizationtoken,
        'client_id=' . $clientId,
        'client_secret=' . $clientSecret,
        'redirect_uri=' . $redirectUri
      );
      $postbody = implode('&',$postbody);


      $headers = array('Content-Type: application/x-www-form-urlencoded');

      $ch = curl_init(OAUTH2_TOKEN_URI); ## suspect error
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
      curl_setopt($ch, CURLOPT_FAILONERROR, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postbody);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_HEADER, 1);
      $result = curl_exec($ch);

      // Retry if certificates are missing.
      if (curl_errno($ch) == CURLE_SSL_CACERT) {
        error_log('[SSL certificate problem] Please check CA. '
          . ' Retrying with the CA cert bundle from bc-api-php-client.');
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '\cacerts.pem');
			  $result = curl_exec($ch);
      }
      $respHeaderSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
      $respHttpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $curlErrorNum = curl_errno($ch);
      $curlError = curl_error($ch);
			curl_close($ch);

      if ($curlErrorNum != CURLE_OK) {
          echo "Throw erre here";
          echo $curlError;
      }


      list($responseHeaders, $responseBody) = self::parseHttpResponse($result, $respHeaderSize);
      print_r($responseHeaders);
      print_r($responseBody);

      $events = json_decode($responseBody, true);

      $url = 'https://api.line.me/v2/profile/';
      $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $events['access_token']);

      $chprofile = curl_init($url);
  		curl_setopt($chprofile, CURLOPT_CUSTOMREQUEST, "GET");
  		curl_setopt($chprofile, CURLOPT_RETURNTRANSFER, true);
  		curl_setopt($chprofile, CURLOPT_POSTFIELDS, 0);
  		curl_setopt($chprofile, CURLOPT_HTTPHEADER, $headers);
  		curl_setopt($chprofile, CURLOPT_FOLLOWLOCATION, 1);
  		$resultProfile = curl_exec($chprofile);
      print_r($resultProfile);
    }
}
