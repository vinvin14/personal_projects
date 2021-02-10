<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementConsumable extends Model
{
    public $timestamps = false;
    public $table = 'movement_consumables';
    protected $fillable = [
        'type',
        'purpose',
        'invoice_number',
        'reference',
        'quantity',
        'date_received_released',
        'received_released_by',
        'remarks',
        'deleted_at',
    ];
}
