<?php 

namespace Mbarwick83\Shorty\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mbarwick83\Previewr\Previewr
 */
class Shorty extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * Don't use this. Just... don't.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Mbarwick83\Shorty\Shorty';
    }
}
