<?php

use App\Models\MDosenTabs;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    redirect('admin');
});