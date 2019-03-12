<?php

namespace Mbarwick83\Shorty\Interfaces;

use Mbarwick83\Shorty\Exceptions\InvalidApiResponseException;

abstract class ShortLinkAbstractInterface
{
    /**
     * Shortens the long given URL
     *
     * @param string $longUrl
     *
     * @throws \Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException
     */
    abstract public function shorten($longUrl);

    /**
     * Expands the short given URL
     *
     * @param string $shortUrl
     *
     * @throws \Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException
     */
    abstract public function expand($shortUrl);

    /**
     * Performs the Request to the API server.
     *
     * @param $url
     * @param $context
     * @return string
     */
    protected function request($url,$context)
    {
        return file_get_contents($url,false,$context);
    }

}
