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
        'status_bimbingan_proposal',
        'status_sidang_proposal',
        'status_bimbingan_skripsi',
        'status_sidang_skripsi',
        'progres_bimbingan_proposal',
        'progres_bimbingan_skripsi',
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
    public function pem_proposal()
    {
        return $this->hasOne(THonorTab::class, 't_mahasiswa_tabs', 'id')->where('m_type_request_id', 1)->where('m_type_request_id_detail', 3);
    }
    public function sid_proposal()
    {
        return $this->hasMany(THonorTab::class, 't_mahasiswa_tabs', 'id')->where('m_type_request_id', 1)->where('m_type_request_id_detail', 4);
    }
    public function sid_proposal_2()
    {
        return $this->hasMany(THonorTab::class, 't_mahasiswa_tabs', 'id')->where('m_type_request_id', 1)->where('m_type_request_id_detail', 4);
    }
    public function sid_proposal_3()
    {
        return $this->hasMany(THonorTab::class, 't_mahasiswa_tabs', 'id')->where('m_type_request_id', 1)->where('m_type_request_id_detail', 4);
    }
    public function pem_skripsi()
    {
        return $this->hasOne(THonorTab::class, 't_mahasiswa_tabs', 'id')->where('m_type_request_id', 2)->where('m_type_request_id_detail', 3);
    }
    public function sid_skripsi()
    {
        return $this->hasMany(THonorTab::class, 't_mahasiswa_tabs', 'id')->where('m_type_request_id', 2)->where('m_type_request_id_detail', 4);
    }
    public function sid_skripsi_2()
    {
        return $this->hasMany(THonorTab::class, 't_mahasiswa_tabs', 'id')->where('m_type_request_id', 2)->where('m_type_request_id_detail', 4);
    }
    public function sid_skripsi_3()
    {
        return $this->hasMany(THonorTab::class, 't_mahasiswa_tabs', 'id')->where('m_type_request_id', 2)->where('m_type_request_id_detail', 4);
    }
    public function pembimbing_two()
    {
        return $this->hasOne(MDosenTabs::class, 'id', 'mentor_two');
    }
}
