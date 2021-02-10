<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'partNumber',
        'referenceDesignator',
        'systemType',
        'boardType',
        'vendor',
        'storedIn',
        'location',
        'unit',
        'description',
        'dateReceived',
        'remarks',
        'dateAdded',
        'maximumQuantity',
        'actualQuantity',
        'minimumQuantity',
        'criticalTagging',
        'unitPrice',
        'totalPrice',
        'rma',
        'entryBy',

    ];
}
