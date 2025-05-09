<?php

namespace App\CMS\Components;

use App\CMS\Component;
use App\CMS\Fields\StringField;
use App\Attrs\AdminAttr;

class AddressComponent extends Component
{
    #[AdminAttr(label: 'Street')]
    public StringField $street;
    
    #[AdminAttr(label: 'City')]
    public StringField $city;
    
    #[AdminAttr(label: 'Notes')]
    public StringField $notes;
}