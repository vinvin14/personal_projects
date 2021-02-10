<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ConsignedOutgoingExport implements FromQuery, WithStyles, WithHeadings, WithMapping, ShouldAutoSize
{
    protected  $date;
    public function __construct($date)
    {
        $this->date = $date;
    }
    public function query()
    {
        return DB::table('movement_consigned')
                        ->leftJoin('consignedspares', 'consignedspares.id', '=', 'movement_consigned.reference')
                        ->leftJoin('systemtypes', 'systemtypes.id', '=', 'consignedspares.systemType')
                        ->leftJoin('boardtypes', 'boardtypes.id', '=', 'consignedspares.boardType')
                        ->leftJoin('locations', 'locations.id', '=', 'consignedspares.storedIn')
                        ->selectRaw('components.unitPrice * movement_consigned.quantity')
                        ->select(
                            'movement_consigned.*',
                            'systemtypes.systemType as systemType',
                            'boardtypes.boardType as boardType',
                            'locations.location as location',
                            'consignedspares.partNumber',
                            'consignedspares.description',
                            'consignedspares.serialNumber',
                            'consignedspares.unitPrice',
                            'consignedspares.invoice_number',
                            'consignedspares.vendor',
                            'consignedspares.actualQuantity as quantity',
                            'totalPrice'
                        )
                        ->whereBetween('movement_consigned.date_received_released', [$this->date['from'], $this->date['to']])
                        ->where(['type' => 'outgoing', 'movement_consigned.deleted_at' => null])
                        ->orderBy('movement_consigned.date_received_released', 'ASC');
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
    public function headings(): array
    {
        return [
            'Part Number',
            'Description',
            'Serial Number',
            'Quantity',
            'Purpose',
//            'RMA #',
            'Date of Release',
//            'Month',
//            'Year',
            'System Type',
            'Board Type',
            'Invoice #',
            'Vendor',
            'Unit Price',
            'Total Price',
            'Location',
            'Received By',
        ];
    }
    public function map($consigned): array
    {
        return [
            $consigned->partNumber,
            $consigned->description,
            $consigned->serialNumber,
            $consigned->quantity,
            $consigned->purpose,
//            $consigned->rma,
            $consigned->date_received_released,
//            $consigned->month,
//            $consigned->year,
            $consigned->systemType,
            $consigned->boardType,
            $consigned->invoice_number,
            $consigned->vendor,
            $consigned->unitPrice,
            $consigned->totalPrice,
            $consigned->location,
            $consigned->received_released_by,
        ];
    }
}
