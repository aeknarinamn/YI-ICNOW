<?php

namespace Mbarwick83\Shorty;

use Mbarwick83\Shorty\Interfaces\ShortLinkAbstractInterface;
use Mbarwick83\Shorty\Exceptions\InvalidApiResponseException;

class Shorty extends ShortLinkAbstractInterface
{

    /**
     * Goo.gl's API Url
     *
     * @var array
     */
    private $api = 'https://www.googleapis.com/urlshortener/v1/url';

    /**
     * Constructor
     *
     * @param string $apiKey A Google API key, optional
     */
    public function __construct()
    {
        $this->apiKey = 'key='. config('shorty.api_key') .'';
    }

    /**
     * Converts a long URL into a short URL with the goo.gl/XXXXX format.
     *
     * @param  string $longUrl
     * @return mixed
     * @throws Exceptions\InvalidApiResponseException
     */
    public function shorten($longUrl)
    {
        if ($this->urlCheck($longUrl)) {
            $request = array
            (
                'http'=> array
                (
                    'method'        =>  "POST",
                    'header'        =>  "Accept-language: en\r\n".
                                        "Content-Type: application/json\r\n",
                    'ignore_errors' => true,
                    'content'       => json_encode(array('longUrl' => $longUrl)),
                )
            );

            $params = (!empty($this->apiKey)) ? '?'.$this->apiKey : '';
            $data = $this->request($this->api.$params, stream_context_create($request));
            $data = json_decode($data,true);
            $data = $this->validate($data,'shorten');

            return $data['id'];
        }

        throw new InvalidApiResponseException('The URL provided for shortening is not valid or currently unavailable.');
    }

    /**
     * Expands an URL with the goo.gl/XXXXX format to its original form.
     *
     * @param  string                                 $shortUrl
     * @return mixed
     * @throws Exceptions\InvalidApiResponseException
     */
    public function expand($shortUrl)
    {
        if ($this->urlCheck($shortUrl)) {
            $params = (!empty($this->apiKey)) ? '?'.implode('&',array($this->apiKey,"shortUrl={$shortUrl}")) : "?shortUrl={$shortUrl}";
            $data = $this->request($this->api.$params,stream_context_create( array('http' => array('ignore_errors' => true) ) ) );
            $data = json_decode($data,true);
            $data = $this->validate($data,'expand');

            return $data['longUrl'];
        }
        throw new InvalidApiResponseException('The URL provided for expansion is not valid or currently unavailable.');
    }

    /**
     * Returns all data available, including statistics from a shortened URL (goo.gl/XXXXX)
     *
     * @param  string                                 $shortUrl
     * @return array
     * @throws Exceptions\InvalidApiResponseException
     */
    public function stats($shortUrl)
    {
        if ($this->urlCheck($shortUrl)) {
            $params = (!empty($this->apiKey)) ? '?'.implode('&',array($this->apiKey,"shortUrl={$shortUrl}","projection=FULL")) : implode('&',array("shortUrl={$shortUrl}","projection=FULL"));
            $data = $this->request($this->api.$params,stream_context_create( array('http' => array('ignore_errors' => true) ) ));
            $data = json_decode($data,true);
            $data = $this->validate($data,'expand');

            return $data;
        }
        throw new InvalidApiResponseException('The URL provided for expansion is not valid or currently unavailable.');
    }



    /**
     * Validates Google URL Shortener response and throws error messages, if any.
     *
     * @param  array                                  $data
     * @param $method
     * @return array
     * @throws Exceptions\InvalidApiResponseException
     */
    protected function validate(&$data, $method)
    {
        if ( is_array($data) === false ) {
            throw new InvalidApiResponseException('Google Url Shortener returned a response that could not be handled');
        }

        if (!empty($data['error']['errors'])) {
            $error = json_encode($data['error']['errors']);
            throw new InvalidApiResponseException('Google Url Shortener returned the following error: '.$error);
        }

        if (empty($data['status']) && $method == 'expand') {
            throw new InvalidApiResponseException('Google Url Shortener has no status');
        }

        if ( !empty($data['status']) && $data['status']!='OK' && $method == 'expand') {
            throw new InvalidApiResponseException('Google Url Shortener returned an invalid status');
        }

        if (empty($data['id'])) {
            throw new InvalidApiResponseException('Google Url Shortener cannot provide the shortened Url');
        }

        if (empty($data['longUrl'])) {
            throw new InvalidApiResponseException('Google Url Shortener cannot provide the original Url');
        }

        return $data;
    }
    /**
     * Validates if the URL to submit actually exists and is accessible.
     *
     * @param $url string                 URL being submitted.
     * @throws \Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException
     * @return boolean
     */
    protected function urlCheck($url)
    {
        // first check if url has http:// prefix, if not, add it
        $parsed = parse_url($url);

        if (empty($parsed['scheme'])) {
            $url = 'http://' . ltrim($url, '/');
        }

        $file_headers = get_headers($url);

        if (!strpos($file_headers[0], "404 Not Found") > 0) {
            return true;
        }
        
        return false;
    }
}
