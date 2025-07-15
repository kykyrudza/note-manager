<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public $fillable = [
        'name',
        'description',
        'completed'
    ];

    public $casts = [
        'name' => 'string',
        'description' => 'string',
        'completed' => 'boolean',
    ];
}
