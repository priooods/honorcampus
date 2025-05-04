<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class THonorTab extends Model
{
    public $fillable = [
        't_mahasiswa_tabs',
        'penguji_satu',
        'penguji_dua',
        'penguji_tiga',
        'honor',
    ];
}
