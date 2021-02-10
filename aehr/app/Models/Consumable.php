<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumable extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'partNumber',
        'actualQuantity',
        'unit',
        'storedIn',
        'location',
        'description',
        'criticalLevel',
        'entryBy',
        'dateAdded',
        'maximumQuantity',
        'criticalTagging',
        'minimumQuantity',
        'remarks',
        'unitPrice',
        'totalPrice',
    ];
}
