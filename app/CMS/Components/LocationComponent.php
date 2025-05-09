<?php

namespace App\CMS\Components;

use App\CMS\Component;
use App\CMS\Fields\StringField;
use App\Attrs\AdminAttr;

class LocationComponent extends Component
{
    #[AdminAttr(label: 'Location')]
    public StringField $name;
    
    #[AdminAttr(label: 'Coordinates')]
    public StringField $coordinates;
}