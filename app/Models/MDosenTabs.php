<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MDosenTabs extends Model
{
    public $fillable = [
        'name',
        'nidn',
        'scope',
        'm_status_tabs_id',
        'users_id'
    ];

    public function status(){
        return $this->hasOne(MStatusTab::class, 'id' ,'m_status_tabs_id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'users_id');
    }
}
