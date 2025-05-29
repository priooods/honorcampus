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

    public function mahasiswa()
    {
        return $this->hasOne(TMahasiswaTab::class, 'id', 't_mahasiswa_tabs');
    }

    public function dosen()
    {
        return $this->hasOne(MDosenTabs::class, 'id', 'm_dosen_tabs_id');
    }

    public function type_request()
    {
        return $this->hasOne(MTypeRequest::class, 'id', 'm_type_request_id');
    }

    public function type_request_detail()
    {
        return $this->hasOne(MTypeRequest::class, 'id', 'm_type_request_id_detail');
    }
}
