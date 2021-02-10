<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RMA extends Model
{
    public $table = 'rma';
    public $timestamps = false;
    protected $fillable = [
        'description',
        'type',
        'batch',
        'customer',
        'totalQuantity',
        'unit',
        'totalRepaired',
        'totalAssigned',
        'dateReceived',
        'receivedBy',
        'totalDefective',
        'remarks',
        'typeOfService',
        'status',
        'record',
    ];
}
