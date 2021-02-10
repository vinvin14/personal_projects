<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 16/12/2020
 * Time: 9:13 PM
 */

namespace App\Http\Controllers\api;


use App\Models\Board;
use Illuminate\Support\Facades\DB;

class BoardController
{
    public function index()
    {
        return Board::orderBy('rma', 'asc')->where('softDeleted', 'no')->get();
    }
    public function show($id)
    {
        $query = DB::table('boards')
                    ->where('boards.id', $id)
                    ->leftJoin('status', 'status.id', '=', 'boards.status')
                    ->leftJoin('locations', 'locations.id', '=', 'boards.storedIn')
                    ->leftJoin('officer', 'officer.id', '=', 'boards.fe')
                    ->leftJoin('systemtypes', 'systemtypes.id', '=', 'boards.systemType')
                    ->leftJoin('typeofservices', 'typeofservices.id', '=', 'boards.typeOfService')
                    ->leftJoin('faultcodes', 'faultcodes.id', '=', 'boards.faultCode')
                    ->select('boards.*', 'status.status as status', 'faultcodes.code as faultCode', 'locations.location as storedIn',
                        'systemtypes.systemType as systemType', 'officer.firstname', 'officer.lastname', 'officer.middlename', 'typeofservices.typeOfService as typeOfService')
                    ->first();
        if(empty($query))
        {
            return response()->json('No Record(s) Found!', 404);
        }
        return response()->json($query, 200);
    }
    public function replacement($id)
    {

    }
    public function repaired_boards_by_date($date)
    {
        return DB::table('boards')
            ->where('status', '=', 2)
            ->where('endOfRepair', $date)
            ->count();
    }
}
