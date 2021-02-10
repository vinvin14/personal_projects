<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaultCode extends Model
{
    public $timestamps = false;
    public $table = 'faultcodes';
    protected $fillable = [
        'code',
        'type',
        'description',
    ];
}
