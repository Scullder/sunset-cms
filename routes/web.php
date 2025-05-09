<?php

use Illuminate\Support\Facades\Route;
use App\CMS\Entities\NewsEntity;

Route::get('/', function () {
    $news = new NewsEntity();
});
