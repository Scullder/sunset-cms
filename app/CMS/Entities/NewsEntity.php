<?php

namespace App\CMS\Entities;

use App\Attrs\AdminAttr;
use App\CMS\Entity;
use App\CMS\Components\AddressComponent;
use App\CMS\Collections\LocationCollection;

class NewsEntity extends Entity
{
    #[AdminAttr(label: 'Address')]
    public AddressComponent $address;

    #[AdminAttr(label: 'Locations')]
    public LocationCollection $locations;
}