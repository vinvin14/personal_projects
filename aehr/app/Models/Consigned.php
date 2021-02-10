<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consigned extends Model
{
    public $timestamps = false;
    public $table = 'consignedspares';
    protected $fillable = [
        'partNumber',
        'serialNumber',
        'description',
        'systemType',
        'boardType',
        'storedIn',
        'location',
        'dateAdded',
        'actualQuantity',
        'remarks',
        'unit',
        'dateReceived',
        'entryBy',
        'maximumQuantity',
        'criticalTagging',
        'minimumQuantity',
        'unitPrice',
        'depreciationValue',
        'usefulLife',
        'totalPrice',
        'vendor',
        'invoice_number',
        'is_out',
        'deleted_at',
    ];
}
