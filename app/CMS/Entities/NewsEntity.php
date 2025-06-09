<?php

namespace App\CMS\Entities;

use App\Attrs\AdminAttr;
use App\Attrs\DBAttr;
use App\CMS\Entity;
use App\CMS\Components\AddressComponent;
use App\CMS\Collections\LocationCollection;
use App\CMS\Collections\LocationGroupCollection;
use App\CMS\Fields\StringField;

class NewsEntity extends Entity
{
    #[AdminAttr(label: 'Name')]
    #[DBAttr(type: 'string', length: 255)]
    public StringField $name;

    #[AdminAttr(label: 'Description')]
    #[DBAttr(type: 'text', length: 255)]
    public StringField $description;

    #[AdminAttr(label: 'Address')]
    public AddressComponent $address;

    #[AdminAttr(label: 'Locations')]
    public LocationCollection $locations;

    // news_entity_locationGroups_
    #[AdminAttr(label: 'Locations groups')]
    public LocationGroupCollection $locationsGroups;
}
