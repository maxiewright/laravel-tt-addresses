<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r'])
    ->each->not->toBeUsed();

arch('models extend eloquent model')
    ->expect('MaxieWright\TrinidadAndTobagoAddresses\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model');

arch('enums are backed by strings')
    ->expect('MaxieWright\TrinidadAndTobagoAddresses\Enums\DivisionType')
    ->toBeStringBackedEnum();

arch('traits are traits')
    ->expect('MaxieWright\TrinidadAndTobagoAddresses\Traits')
    ->toBeTraits();

arch('seeders extend seeder')
    ->expect('MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders')
    ->toExtend('Illuminate\Database\Seeder');
