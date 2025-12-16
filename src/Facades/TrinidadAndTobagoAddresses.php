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
        return \MaxieWright\TrinidadAndTobagoAddresses\TrinidadAndTobagoAddresses::class;
    }
}
