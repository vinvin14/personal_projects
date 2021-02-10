<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user',
        'module',
        'details',
    ];
}
