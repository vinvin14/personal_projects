<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfService extends Model
{
    public $timestamps = false;
    public $table = 'typeofservices';
    protected $fillable = [
        'typeOfService',
        'description',
        'addedBy',
    ];
}
