<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementComponent extends Model
{
    public $timestamps = false;
    public $table = 'movement_components';
    protected $fillable = [
        'type',
        'purpose',
        'rma',
        'reference_designator',
        'invoice_number',
        'vendor',
        'brand',
        'reference',
        'quantity',
        'date_received_released',
        'received_released_by',
        'remarks',
        'deleted_at',
    ];
}
