<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TMahasiswaTab extends Model
{
    public $fillable = [
        't_periode_tabs',
        'name',
        'nim',
        'status_proposal',
        'status_skripsi',
        'mentor_one',
        'mentor_two',
        'm_status_tabs_id'
    ];
}
