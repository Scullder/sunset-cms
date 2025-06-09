<?php

namespace App\CMS\Components;

use App\Attrs\AdminAttr;
use App\Attrs\DBAttr;
use App\CMS\Component;
use App\CMS\Fields\StringField;


class AddressComponent extends Component
{
    #[AdminAttr(label: 'Street')]
    #[DBAttr(type: 'string')]
    public StringField $street;
    
    #[AdminAttr(label: 'City')]
    #[DBAttr(type: 'string')]
    public StringField $city;
    
    #[AdminAttr(label: 'Notes')]
    #[DBAttr(type: 'string')]
    public StringField $notes;
}