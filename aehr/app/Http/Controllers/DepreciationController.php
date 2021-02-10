<?php
/**
 * Created by PhpStorm.
 * User: DOH-VIN
 * Date: 23/12/2020
 * Time: 9:08 AM
 */

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;

class DepreciationController
{
    public function equipments()
    {
        return DB::table('equipment')->where('usefulLife', '!=', '')->get();
    }
    public function consigned()
    {
        return DB::table('consignedspares')->where('usefulLife', '!=', '')->get();
    }
    public function tools()
    {
        return DB::table('tools')->where('usefulLife', '!=', '')->get();
    }
    public function update()
    {
        if(empty($equipment = $this->equipments()))
        {
            foreach ($equipment as $row)
            {
                if(date('Y-m-d') >= $row->dateReceived)
                {
                    $depreciation = ($row->depreciationValue / $row->usefulLife);
                    DB::table('equipment')->update(['depreciationValue' => $depreciation]);
                }
            }
        }
        if(empty($consigned = $this->consigned()))
        {
            foreach ($consigned as $row)
            {
                if(date('Y-m-d') >= $row->dateReceived)
                {
                    $depreciation = ($row->depreciationValue / $row->usefulLife);
                    DB::table('equipment')->update(['depreciationValue' => $depreciation]);
                }
            }
        }
        if(empty($tools = $this->tools()))
        {
            foreach ($tools as $row)
            {
                if(date('Y-m-d') >= $row->dateReceived)
                {
                    $depreciation = ($row->depreciationValue / $row->usefulLife);
                    DB::table('equipment')->update(['depreciationValue' => $depreciation]);
                }
            }
        }
    }
}
