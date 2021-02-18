<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ConsumableOutgoingExport implements FromQuery, WithHeadings,ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
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
        return DB::table('movement_consumables')
            ->leftJoin('purposes', 'purposes.id', '=', 'movement_consumables.purpose')
            ->leftJoin('consumables', 'consumables.id', '=', 'movement_consumables.reference')
            ->leftJoin('locations', 'locations.id', '=', 'consumables.storedIn')
            ->select(
                'consumables.partNumber as partNumber',
                'consumables.description as description',
                'movement_consumables.quantity as quantity',
                'purposes.purpose as movement_purpose',
                'movement_consumables.date_received_released',
                'movement_consumables.vendor',
                'consumables.unitPrice',
                DB::raw('consumables.unitPrice * movement_consumables.quantity as movement_total_price'),
                'locations.location as location',
//                'purposes.purpose as purpose',
                'movement_consumables.received_released_by',
                )
            ->where(['type' => 'outgoing', 'deleted_at' => null])
            ->whereBetween('movement_consumables.date_received_released', [$this->date['from'], $this->date['to']])
            ->orderBy('movement_consumables.date_received_released', 'ASC');
    }
    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_CURRENCY_USD,
            'J' => NumberFormat::FORMAT_CURRENCY_USD,
        ];
    }
    public function headings() :array
    {
        return [
            'Part Number',
            'Description',
            'Quantity',
            'Purpose',
            'Date Released',
            'Vendor',
            'Unit Price',
            'Total Price',
            'Location',
            'Received By',
        ];
    }
    public function map($consumable): array
    {
        return [
            $consumable->partNumber,
            $consumable->description,
            $consumable->quantity,
            $consumable->movement_purpose,
            $consumable->date_received_released,
            $consumable->vendor,
            $consumable->unitPrice,
            $consumable->movement_total_price,
            $consumable->location,
            $consumable->received_released_by,
//            $consumable->purpose,

        ];
    }
}
