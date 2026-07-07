<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    protected $fillable = [
        'name',
        'role',
        'description',
        'image',
        'sort_order',
    ];
}
