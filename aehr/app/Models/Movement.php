<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'reference',
        'origin',
        'type',
        'serialNumber',
        'modelNumber',
        'brand',
        'quantity',
        'systemType',
        'boardType',
        'invoiceNumber',
        'calibrationReq',
        'calibrationDate',
        'vendor',
        'receivedBy',
        'dateReceived',
        'month',
        'year',
        'entryBy',
        'remarks',
        'unitPrice',
        'totalPrice',
        'dateAdded',
        'purpose',
        'rma',
        'referenceDesignator',
        'depreciationValue',
        'restriction',
        'storedIn',
        'location',
    ];
}
