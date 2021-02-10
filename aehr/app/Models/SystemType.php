<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemType extends Model
{
    public $timestamps = false;
    public $table = 'systemtypes';
    protected $fillable = [
        'systemType',
        'description',
        'addedBy',
    ];
}
