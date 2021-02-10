<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementConsigned extends Model
{
    public $timestamps = false;
    public $table = 'movement_consigned';
    protected $fillable = [
        'purpose',
        'invoice_number',
        'reference',
        'quantity',
        'date_received_released',
        'type',
        'received_released_by',
        'remarks',
        'deleted_at',
    ];
    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }
}
