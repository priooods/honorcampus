<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTypeRequest extends Model
{
    public $timestamps = false;
    public $fillable = [
        'title',
    ];
}
