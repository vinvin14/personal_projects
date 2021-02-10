<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FSE extends Model
{
    public $timestamps = false;
    public $table = 'officer';
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'position',
        'whereAbouts',
        'officerStatus',
        'startOfService',
    ];
}
