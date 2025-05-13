<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TMahasiswaTab extends Model
{
    public $fillable = [
        't_periode_tabs',
        'name',
        'nim',
        'prodi',
        'status_proposal',
        'status_skripsi',
        'm_status_tabs_id'
    ];

    public function periode()
    {
        return $this->hasOne(TPeriodeTab::class, 'id', 't_periode_tabs');
    }
    public function status()
    {
        return $this->hasOne(MStatusTab::class, 'id', 'm_status_tabs_id');
    }
    public function honor()
    {
        return $this->hasMany(THonorTab::class, 't_mahasiswa_tabs', 'id');
    }
    public function pembimbing_two()
    {
        return $this->hasOne(MDosenTabs::class, 'id', 'mentor_two');
    }
}
