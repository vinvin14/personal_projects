<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementEquipment extends Model
{
    public $timestamps = false;
    public $table = 'movement_equipment';
    protected $fillable = [
        'purpose',
        'reference',
        'quantity',
        'date_received',
        'type',
        'received_by',
        'remarks',
        'deleted_at',
    ];
    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }
}
