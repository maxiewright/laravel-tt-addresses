<?php

namespace MaxieWright\TrinidadAndTobagoAddresses\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MaxieWright\TrinidadAndTobagoAddresses\TrinidadAndTobagoAddresses
 */
class TrinidadAndTobagoAddresses extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        // Return the container binding key, not the class name
        return 'tt-addresses';
    }
}
