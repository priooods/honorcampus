<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPeriodeTab extends Model
{
    public $fillable = [
        'title',
        'start_date',
        'end_date',
        'm_status_tabs_id'
    ];
}
