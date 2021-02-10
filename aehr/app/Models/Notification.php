<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;
    protected $table = 'notification';
    protected $fillable = [
        'item',
        'details',
        'seen',
        'status',
        'type',
        'created',
        'origin',
        'user',
        'link',
    ];
}
