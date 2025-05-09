<?php

namespace App\CMS\Collections;

use App\CMS\Collection;
use App\CMS\Components\LocationComponent;

class LocationCollection extends Collection
{
    protected string $itemType = LocationComponent::class;
}
