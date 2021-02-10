<?php
/**
 * Created by PhpStorm.
 * User: DOH-VIN
 * Date: 21/12/2020
 * Time: 3:22 PM
 */

namespace App\Http\Controllers\api;


use Illuminate\Support\Facades\DB;

class ConsignedController
{
    public function getByPartNum($partNumber)
    {
        return DB::table('consignedspares')
                    ->leftJoin('units', 'units.id', '=', 'consignedspares.unit')
                    ->leftJoin('locations', 'locations.id', '=', 'consignedspares.storedIn')
                    ->leftJoin('systemtypes', 'systemtypes.id', '=', 'consignedspares.systemType')
                    ->leftJoin('boardtypes', 'boardtypes.id', '=', 'consignedspares.boardType')
                    ->select('consignedspares.*', 'units.id as unitID', 'units.unit as unit', 'locations.id as storedInID', 'locations.location as storedIn',
                        'systemtypes.id as systemTypeID', 'systemtypes.systemType as systemType', 'boardtypes.id as boardTypeID', 'boardtypes.boardType as boardType')
                    ->where(
                        [
                            'consignedspares.partNumber' => $partNumber,
                            'is_out' => 0,
                            'deleted_at' => null
                        ]
                    )
                    ->get();
    }
}
