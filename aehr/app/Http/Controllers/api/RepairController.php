<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 16/12/2020
 * Time: 10:00 PM
 */

namespace App\Http\Controllers\api;


use Illuminate\Support\Facades\DB;

class RepairController
{
    public function boards($motherRecord, $type)
    {
        switch ($type)
        {
            case 'all':
                return DB::table('boards')
                            ->leftJoin('status', 'status.id', '=', 'boards.status')
                            ->where('motherRecord', $motherRecord)
                            ->select('boards.*', 'status.status as status')
                            ->get();
                break;
            case 'forRepair':
                return DB::table('boards')
                            ->leftJoin('status', 'status.id', '=', 'boards.status')
                            ->where(['motherRecord' => $motherRecord, 'boards.status' => 1])
                            ->select('boards.*', 'status.status as status')
                            ->get();
                break;
            case 'repaired':
                return DB::table('boards')
                            ->leftJoin('status', 'status.id', '=', 'boards.status')
                            ->where(['motherRecord' => $motherRecord, 'boards.status' => 2])
                            ->select('boards.*', 'status.status as status')
                            ->get();
                break;
            case 'unrepairable':
                return DB::table('boards')
                            ->leftJoin('status', 'status.id', '=', 'boards.status')
                            ->where(['motherRecord' => $motherRecord, 'boards.status' => 4])
                            ->select('boards.*', 'status.status as status')
                            ->get();
                break;
        }
    }
    public function boards_repaired_by_date($date)
    {
        return DB::table('boards')
                    ->where('status', '=', 2)
                    ->where('endOfRepair', $date)
                    ->count();
    }
}
