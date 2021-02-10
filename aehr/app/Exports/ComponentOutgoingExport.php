<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ComponentOutgoingExport implements FromQuery, WithStyles, WithHeadings, WithMapping, ShouldAutoSize
{
    protected  $date;
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($date)
    {
        $this->date = $date;
    }
    public function query()
    {
        return DB::table('movement_components')
                    ->leftJoin('components', 'components.id', '=', 'movement_components.reference')
                    ->leftJoin('locations', 'locations.id', '=', 'components.storedIn')
                    ->leftJoin('purposes', 'purposes.id', '=', 'movement_components.purpose')
                    ->leftJoin('boards', 'boards.id', '=', 'movement_components.rma')
                    ->leftJoin('systemtypes', 'systemtypes.id', '=', 'boards.systemType')
//                    ->leftJoin('boardtypes', 'boardtypes.id', '=', 'boards.boardType')
                    ->selectRaw('components.unitPrice * movement_components.quantity')
                    ->select(
                        'movement_components.*',
                        'locations.location as storedIn',
                        'components.partNumber',
                        'components.description',
                        'components.unitPrice',
                        'totalPrice',
//                        'components.referenceDesignator',
                        'purposes.purpose as purpose',
                        'systemtypes.systemType as systemType',
//                        'boardtypes.boardType as boardType',
                        'boards.rma as rma'
                    )
                    ->whereBetween('movement_components.date_received_released', [$this->date['from'], $this->date['to']])
                    ->where(['type' => 'outgoing', 'deleted_at' => null])
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
        return [
            'Part Number',
            'Description',
            'Reference Designator',
            'Quantity',
            'Purpose',
            'RMA #',
            'Date Released',
//            'Month',
//            'Year',
            'System Type',
//            'Invoice #',
//            'Vendor',
            'Unit Price',
            'Total Price',
            'Location',
            'Received By',
        ];
    }
    public function map($components): array
    {
        return [
            $components->partNumber,
            $components->description,
            $components->reference_designator,
            $components->quantity,
            $components->purpose,
            $components->rma,
            $components->date_received_released,
            $components->systemType,
            $components->unitPrice,
            $components->totalPrice,
            $components->storedIn,
            $components->received_released_by,
        ];
    }
}
