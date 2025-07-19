<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MHonorDosenTabs extends Model
{
    public $timestamps = false;
    public $fillable = [
        't_periode_tabs',
        'type',
        'price',
    ];

    public function periode(){
        return $this->hasOne(TPeriodeTab::class,'id', 't_periode_tabs');
    }
}
