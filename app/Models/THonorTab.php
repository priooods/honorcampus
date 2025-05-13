<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class THonorTab extends Model
{
    public $fillable = [
        't_mahasiswa_tabs',
        'sequent',
        'm_dosen_tabs_id',
        'honor',
        'm_type_request_id',
        'm_type_request_id_detail',
    ];
}
