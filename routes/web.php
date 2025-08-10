<?php

use App\Http\Controllers\HelperController;
use App\Models\MDosenTabs;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    redirect('admin');
});

Route::get('helper/pdf/{t_mahasiswa_tabs}/{m_dosen_tabs_id}', [HelperController::class, 'index'])->name('pdf.report');