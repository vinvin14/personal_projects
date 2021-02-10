<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'rma',
        'description',
        'serialNumber',
        'partNumber',
        'storedIn',
        'location',
        'faultCode',
        'faultDetails',
        'dateReceived',
        'startOfRepair',
        'remarks',
        'status',
        'fe',
        'receivedBy',
        'entryBy',
        'repPartNum',
        'motherRecord',
        'slot',
        'typeOfService',
        'systemType',
        'EWSFindings',
        'findings',
        'causeOfFC',
        'causeOfFCDetails',
        'partUsage',
        'turnAroundTime',
        'benchTestFindings',
        'workPerformed',
        'endOfRepair',
        'upgradeTime',
        'testTime',
        'repairTime',
        'replacementPart',
        'replacementPartDesc',
        'reasonForReturn',
        'operatingSystem',
        'jobStatus',
        'softDeleted',
        'incomingTrackingNumber',
        'outgoingTrackingNumber',
        'shipToCustomerName',
        'address',
        'dateShipped',
        'contactPerson',
        'contactNumber',
    ];
}
