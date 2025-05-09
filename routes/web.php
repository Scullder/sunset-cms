<?php

use Illuminate\Support\Facades\Route;
use App\CMS\Entities\NewsEntity;
use App\CMS\Components\LocationComponent;
use App\CMS\Collections\LocationCollection;
use App\CMS\Field;

Route::get('/', function () {
    $news = new NewsEntity();
    
    // $location = new LocationComponent();
    // $location->coordinates->set('123.23, 993.23');
    // $location->name->set('CIty 1');
    
    // $locations = new LocationCollection();
    // $locations->add($location);

    // // $news->locations->add($location);
    // $news->locationsGroups->add($locations);

    // $hash = $news->locationsGroups[0][0]->name->getInputName();
    // $a = Field::getPathFromHash($hash);
});
