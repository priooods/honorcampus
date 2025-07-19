<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MDosenTabs extends Model
{
    public $fillable = [
        'name',
        'nidn',
        'm_keahlian_dosen_tabs_id',
        'm_status_tabs_id',
        'users_id'
    ];

    public function status(){
        return $this->hasOne(MStatusTab::class, 'id' ,'m_status_tabs_id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'users_id')->where('m_user_roles_id', 3);
    }

    public function keahlian()
    {
        return $this->hasOne(MKeahlianDosenTabs::class, 'id', 'm_keahlian_dosen_tabs_id');
    }
}
