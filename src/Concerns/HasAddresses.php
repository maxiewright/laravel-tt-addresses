<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use MaxieWright\TrinidadAndTobagoAddresses\Enums\AddressType;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Address;

/**
 * Trait for models that have polymorphic addresses.
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Address> $addresses
 * @property-read Address|null $primaryAddress
 * @property-read Address|null $homeAddress
 * @property-read Address|null $workAddress
 */
trait HasAddresses
{
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function primaryAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')
            ->where('is_primary', true);
    }

    public function addressOfType(AddressType $type): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')
            ->where('type', $type);
    }

    public function homeAddress(): MorphOne
    {
        return $this->addressOfType(AddressType::Home);
    }

    public function workAddress(): MorphOne
    {
        return $this->addressOfType(AddressType::Work);
    }
}
