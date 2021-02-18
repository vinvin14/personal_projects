<?php

namespace App\Exports;


use App\User;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RepairExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    protected  $date;
    public function __construct($date)
    {
        $this->date = $date;
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function query()
    {
        return DB::table('boards')
                    ->leftJoin('repairrecords', 'repairrecords.id', '=', 'boards.motherRecord')
                    ->leftJoin('customers', 'customers.id', '=', 'repairrecords.customer')
                    ->leftJoin('faultcodes', 'faultcodes.id', '=', 'boards.faultCode')
                    ->leftJoin('systemTypes', 'systemTypes.id', '=', 'boards.systemType')
                    ->leftJoin('movement_components', 'movement_components.rma', '=', 'boards.id')
                    ->leftJoin('components', 'components.id', '=', 'movement_components.reference')
                    ->leftJoin('typeofservices', 'typeofservices.id', '=', 'boards.typeOfService')
                    ->leftJoin('replacementparts', 'replacementparts.rma', '=', 'boards.id')
                    ->select(
                        'boards.*',
                        'repairrecords.*',
                        'customers.name as customer',
                        'faultcodes.code as faultCode',
                        'faultcodes.type as faultType',
                        'repairrecords.shipDate as shipDate',
                        'repairrecords.outgoingTracking as outgoingTracking',
                        'repairrecords.incomingTracking as incomingTracking',
                        'components.partNumber as replacementPartNumber',
                        'components.description as replacementDescription',
                        'movement_components.quantity as replacementQuantity',
                        'movement_components.reference_designator as reference_designator',
                        'components.referenceDesignator as referenceDesignator',
                        'typeofservices.typeOfService as typeOfService',
                        'systemtypes.systemType as systemType',
                    )
                    ->where('repairrecords.softDeleted', 'no')
                    ->whereBetween('boards.dateReceived', [$this->date['from'], $this->date['to']])
                    ->orderBy('dateReceived', 'ASC');
    }
    public function headings(): array
    {
        return [
            'RMA Number',
            'Customer Name',
            'Part Number',
            'Description',
            'Reason For Return',
            'Fault Code',
            'Fault Type',
            'Fault Details',
            'Date Received',
            'Start Repair Date',
            'End Repair Date',
            'Type of Service',
            'Operating System',
            'System Type',
            'Incoming Tracking Info',
            'Replacement Part Number',
            'Replacement Part Description',
            'Replacement Part Quantity',
            'Reference Designator',
            'Bench Test Findings',
            'EWS Findings',
            'Findings',
            'Cause of Fault Category',
            'Work Performed',
            'Upgrade Time (Hours)',
            'Test Time (Hours)',
            'Repair Time (Hours)',
            'Ship Date',
            'Outgoing Tracking Info',
        ];
    }
    public function map($repair): array
    {
        return [
            $repair->rma,
            $repair->customer,
            $repair->partNumber,
            $repair->description,
            $repair->reasonForReturn,
            $repair->faultCode,
            $repair->faultType,
            $repair->faultDetails,
            $repair->dateReceived,
            $repair->startOfRepair,
            $repair->endOfRepair,
            $repair->typeOfService,
            $repair->operatingSystem,
            $repair->systemType,
            $repair->incomingTrackingNumber,
            $repair->replacementPartNumber,
            $repair->replacementDescription,
            $repair->replacementQuantity,
            $repair->reference_designator,
            $repair->benchTestFindings,
            $repair->EWSFindings,
            $repair->findings,
            $repair->causeOfFC,
            $repair->workPerformed,
            $repair->upgradeTime,
            $repair->testTime,
            $repair->repairTime,
            $repair->dateShipped,
            $repair->outgoingTrackingNumber,
        ];
    }
}
