<?php

use App\Http\Controllers\HelperController;
use App\Models\MDosenTabs;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    redirect('admin');
});

Route::get('helper/pdf/{id}', [HelperController::class, 'index'])->name('pdf.report');