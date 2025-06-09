<?php

namespace App\CMS\Components;

use App\Attrs\AdminAttr;
use App\Attrs\DBAttr;
use App\CMS\Component;
use App\CMS\Fields\StringField;

class LocationComponent extends Component
{
    #[AdminAttr(label: 'Location')]
    #[DBAttr(type: 'string', length: 255)]
    public StringField $name;

    #[AdminAttr(label: 'Coordinates')]
    #[DBAttr(type: 'string', length: 255)]
    public StringField $coordinates;
}