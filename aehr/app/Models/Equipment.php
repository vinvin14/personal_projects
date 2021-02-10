<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    public $timestamps = false;
    public $table = 'equipment';
    protected $fillable = [
        'partNumber',
        'serialNumber',
        'modelNumber',
        'vendor',
        'brand',
        'description',
        'actualQuantity',
        'dateReceived',
        'remarks',
        'storedIn',
        'location',
        'unit',
        'dateAdded',
        'entryBy',
        'criticalTagging',
        'depreciationValue',
        'unitPrice',
        'totalPrice',
        'usefulLife',
        'invoice_number',
        'is_out',
        'deleted_at',

    ];
    public function MovementEquipment()
    {
        return $this->belongsTo(MovementEquipment::class);
    }
}
