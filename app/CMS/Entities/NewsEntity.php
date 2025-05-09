<?php

namespace App\CMS\Entities;

use App\Attrs\AdminAttr;
use App\CMS\Entity;
use App\CMS\Components\AddressComponent;
use App\CMS\Collections\LocationCollection;
use App\CMS\Collections\LocationGroupCollection;
use App\CMS\Fields\StringField;

class NewsEntity extends Entity
{
    #[AdminAttr(label: 'Name')]
    public StringField $name;

    #[AdminAttr(label: 'Description')]
    public StringField $description;

    #[AdminAttr(label: 'Address')]
    public AddressComponent $address;

    #[AdminAttr(label: 'Locations')]
    public LocationCollection $locations;

    #[AdminAttr(label: 'Locations groups')]
    public LocationGroupCollection $locationsGroups;
}
