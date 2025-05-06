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

    public function periode()
    {
        return $this->hasOne(TPeriodeTab::class, 'id', 't_periode_tabs');
    }

    public function pembimbing_one()
    {
        return $this->hasOne(MDosenTabs::class, 'id', 'mentor_one');
    }
    public function pembimbing_two()
    {
        return $this->hasOne(MDosenTabs::class, 'id', 'mentor_two');
    }
}
