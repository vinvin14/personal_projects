<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EquipmentExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        return DB::table('movement_equipment')
                            ->leftJoin('equipment', 'equipment.id', '=', 'movement_equipment.reference')
                            ->leftJoin('locations', 'locations.id', '=', 'equipment.storedIn')
                            ->selectRaw('equipment.unitPrice * movement_equipment.quantity')
                            ->select(
                                'movement_equipment.*',
                                'locations.location as location',
                                'equipment.partNumber',
                                'equipment.description',
                                'equipment.brand',
                                'equipment.modelNumber',
                                'equipment.serialNumber',
                                'equipment.vendor',
                                'equipment.actualQuantity as quantity',
                                'equipment.unitPrice',
                                'equipment.totalPrice',
                                'equipment.depreciationValue',
                                'equipment.invoice_number',
                            )
                            ->whereBetween('movement_equipment.date_received', [$this->date['from'], $this->date['to']])
                            ->where(['type' => 'incoming', 'movement_equipment.deleted_at' => null])
                            ->orderBy('movement_equipment.date_received', 'ASC');
    }
    public function headings(): array
    {
        return [
            'Description',
            'Brand',
            'Model Number',
            'Part Number',
            'Serial Number',
            'Vendor',
            'Date Received',
            'Quantity',
            'Unit Price',
            'Total Price',
            'Location',
            'Depreciation Value',
            'Invoice Number',
        ];
    }
    public function map($equipment): array
    {
        return [
            $equipment->description,
            $equipment->brand,
            $equipment->modelNumber,
            $equipment->partNumber,
            $equipment->serialNumber,
            $equipment->vendor,
            $equipment->date_received,
            $equipment->quantity,
            $equipment->unitPrice,
            $equipment->totalPrice,
            $equipment->location,
            $equipment->depreciationValue,
            $equipment->invoice_number,
        ];
    }
}
