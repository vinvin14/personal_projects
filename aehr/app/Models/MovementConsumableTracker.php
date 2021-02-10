<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementConsumableTracker extends Model
{
    public $timestamps = false;
    public $table = 'movement_consumable_tracker';
    protected $fillable = [
        'type',
        'item_movement',
        'consumable',
        'previous_quantity',
        'new_quantity',
        'previous_unit_price',
        'new_unit_price',
        'date_of_transaction',
        'entry_by',
        'status',
    ];
}
