<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairRecords extends Model
{
    public $timestamps = false;
    public $table = 'repairrecords';
    protected $fillable = [
        'description',
        'type',
        'batch',
        'customer',
        'totalQuantity',
        'unit',
        'totalRepaired',
        'boardsForRepair',
        'transactionDate',
        'receivedBy',
        'totalDefective',
        'remarks',
        'typeOfService',
        'status',
        'record',
        'shipToCustomerName',
        'shipDate',
        'incomingTracking',
        'outgoingTracking',
        'address',
        'contactPerson',
        'contactNumber',
        'softDeleted',
    ];
}
