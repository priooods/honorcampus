<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MUserRole extends Model
{
    public $timestamps = false;
    public $fillable = [
        'title',
    ];
}
