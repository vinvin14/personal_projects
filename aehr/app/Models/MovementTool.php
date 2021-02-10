<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementTool extends Model
{
    public $timestamps = false;
    public $table = 'movement_tool';
    protected $fillable = [
        'purpose',
        'reference',
        'quantity',
        'date_received_released',
        'type',
        'received_released_by',
        'remarks',
        'deleted_at',
    ];

}
