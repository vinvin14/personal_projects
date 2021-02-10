<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ToolExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected  $date;
    public function __construct($date)
    {
        $this->date = $date;
    }
    public function query()
    {
        return DB::table('movement_tool')
                    ->leftJoin('tools', 'tools.id', '=', 'movement_tool.reference')
                    ->leftJoin('locations', 'locations.id', '=', 'tools.storedIn')
                    ->selectRaw('tools.unitPrice * movement_tool.quantity as totalPrice')
                    ->select(
                        'movement_tool.*',
                        'tools.description',
                        'tools.brand',
                        'tools.vendor',
                        'tools.actualQuantity as quantity',
                        'tools.unitPrice',
                        'totalPrice',
                        'tools.modelNumber',
                        'tools.invoice_number',
                        'tools.calibrationReq',
                        'tools.calibrationDate',
                        'locations.location as location'
                    )
                    ->whereBetween('movement_tool.date_received_released', [$this->date['from'], $this->date['to']])
                    ->where(['type' => 'incoming', 'movement_tool.deleted_at' => null])
                    ->orderBy('movement_tool.date_received_released', 'ASC');
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
            'Description',
            'Brand',
            'Model Number',
            'Vendor',
            'Date Received',
            'Quantity',
            'Unit Price',
            'Total Price',
            'Location',
            'Calibration Requirement',
            'Calibration Date',
            'Invoice Number',
        ];
    }
    public function map($tools): array
    {
        // TODO: Implement map() method.
        return [
            $tools->description,
            $tools->brand,
            $tools->modelNumber,
            $tools->vendor,
            $tools->date_received_released,
            $tools->quantity,
            $tools->unitPrice,
            $tools->unitPrice,
            $tools->location,
            $tools->calibrationReq,
            $tools->calibrationDate,
            $tools->invoice_number,
        ];
    }
}
