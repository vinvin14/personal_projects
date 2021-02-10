<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'partNumber',
        'modelNumber',
        'description',
        'brand',
        'actualQuantity',
        'dateReceived',
        'remarks',
        'vendor',
        'storedIn',
        'location',
        'unit',
        'dateAdded',
        'entryBy',
        'maximumQuantity',
        'minimumQuantity',
        'criticalTagging',
        'depreciationValue',
        'usefulLife',
        'unitPrice',
        'totalPrice',
        'calibrationReq',
        'calibrationDate',
        'invoice_number',
        'is_out',
        'deleted_at',
        'origin',
        'receivedBy',

    ];
}
