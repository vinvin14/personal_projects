<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ComponentIncomingExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected  $date;
    public function __construct($date)
    {
        $this->date = $date;
    }
    public function query()
    {
        return DB::table('movement_components')
                    ->leftJoin('components', 'components.id', '=', 'movement_components.reference')
                    ->leftJoin('locations', 'locations.id', '=', 'components.storedIn')
                    ->select('components.unitPrice * movement_components.quantity as totalPrice')
                    ->select(
                        'movement_components.*',
                        'locations.location as storedIn',
                        'components.partNumber',
                        'components.description',
                        'components.unitPrice',
                        'totalPrice'
                    )
                    ->whereBetween('movement_components.date_received_released', [$this->date['from'], $this->date['to']])
                    ->where(['type' => 'incoming', 'deleted_at' => null])
                    ->orderBy('movement_components.date_received_released', 'ASC');
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
        // TODO: Implement headings() method.
        return [
            'Part Number',
            'Description',
            'Quantity',
            'Date Received',
//            'Month',
//            'Year',
            'Invoice Number',
            'Vendor',
            'Unit Price',
            'Total Price',
            'Location',
            'Received By',
        ];
    }
    public function map($components): array
    {
        // TODO: Implement map() method.
        return [
            $components->partNumber,
            $components->description,
            $components->quantity,
            $components->date_received_released,
//            $components->month,
//            $components->year,
            $components->invoice_number,
            $components->vendor,
            $components->unitPrice,
            $components->totalPrice,
            $components->storedIn,
            $components->received_released_by,
        ];
    }
}
