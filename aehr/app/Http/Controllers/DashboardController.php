<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function index()
    {
//        asdfasdf
        $unrepairable = $this->unrepairable();
        $repaired = $this->repaired();
        $for_repair = $this->for_repair();
        $notifications = $this->notifications();
        list($open_rma, $count_open_rma) = $this->open_rma();
        list($board_types, $count_board_type) = $this->board_type();
        $weekly_rma_released = $this->weekly_rma_released();
        return view('dashboard')
                    ->with('sidebar_selected', 'Dashboard')
                    ->with(compact('unrepairable'))
                    ->with(compact('repaired'))
                    ->with(compact('for_repair'))
                    ->with(compact('notifications'))
                    ->with(compact('open_rma'))
                    ->with(compact('count_open_rma'))
                    ->with(compact('board_types'))
                    ->with(compact('count_board_type'))
                    ->with(compact('weekly_rma_released'));
    }
    public function notifications()
    {
        return DB::table('notification')->where('status', 0)->count();
    }
    public function unrepairable()
    {
        return DB::table('boards')
                    ->leftJoin('repairrecords', 'repairrecords.id', '=', 'boards.motherRecord')
                    ->where('repairrecords.status', 'Active')
                    ->where('boards.status', 4)
                    ->count();
    }
    public function for_repair()
    {
        return DB::table('boards')
                    ->leftJoin('repairrecords', 'repairrecords.id', '=', 'boards.motherRecord')
                    ->where('repairrecords.status', 'Active')
                    ->where('boards.status', 1)
                    ->count();
    }
    public function repaired()
    {
        return DB::table('boards')
                    ->leftJoin('repairrecords', 'repairrecords.id', '=', 'boards.motherRecord')
                    ->where('repairrecords.status', 'Active')
                    ->where('boards.status', 2)
                    ->count();
    }
    public function open_rma()
    {
        $open_rma = DB::table('boards')
                        ->leftJoin('repairrecords', 'repairrecords.id', '=', 'boards.motherRecord')
                        ->leftJoin('customers', 'customers.id', '=', 'repairrecords.customer')
                        ->groupBy('repairrecords.customer')
                        ->orderBy('repairrecords.customer', 'ASC')
                        ->where('boards.status', 1)
            //                                        ->whereBetween('dateReceived', [$year.'-'.$month.'-'.'01', $year.'-'.$month.'-'.$days])
                        ->select(
                            'boards.*',
                            'customers.name',
                            DB::raw('count(boards.rma) as board_count')
                        )
                        ->get();
       $count_open_rma = array();
        foreach ($open_rma as $row) {
            array_push($count_open_rma, $row->board_count);
        }
        return [$open_rma, $count_open_rma];
    }
    public function board_type()
    {
        $board_types = DB::table('repairrecords')
                    ->groupBy('description')
                    ->leftJoin('boards', 'boards.motherRecord', '=', 'repairrecords.id')
                    ->where('boards.status', 1)
                    ->select(
                        'repairrecords.*',
                        DB::raw('count(boards.id) as total_rma')
                    )
                    ->get();
        $total_bt_count = array();
        foreach($board_types as $board_type) {
            array_push($total_bt_count, $board_type->total_rma);
        }
        return [$board_types, $total_bt_count];
    }
    public function weekly_rma_released()
    {
        $monday = strtotime("last monday");
        $monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
        $mon = date("Y-m-d", strtotime(date("Y-m-d",$monday)));
        $tue = date("Y-m-d", strtotime(date("Y-m-d",$monday)." +1 days"));
        $wed = date("Y-m-d", strtotime(date("Y-m-d",$monday)." +2 days"));
        $thu = date("Y-m-d", strtotime(date("Y-m-d",$monday)." +3 days"));
        $fri = date("Y-m-d", strtotime(date("Y-m-d",$monday)." +4 days"));
        $sat = date("Y-m-d", strtotime(date("Y-m-d",$monday)." +5 days"));
        $sun = date("Y-m-d", strtotime(date("Y-m-d",$monday)." +6 days"));

        $mon_rma = DB::table('boards')
            ->where(
                [
                    'endOfRepair' => $mon,
                    'status' => 2
                ]
            )
            ->count();
        $tue_rma = DB::table('boards')
            ->where(
                [
                    'endOfRepair' => $tue,
                    'status' => 2,
                ]
            )
            ->count();
        $wed_rma = DB::table('boards')
            ->where(
                [
                    'endOfRepair' => $wed,
                    'status' => 2,
                ]
            )
            ->count();
        $thu_rma = DB::table('boards')
            ->where(
                [
                    'endOfRepair' => $thu,
                    'status' => 2,
                ]
            )
            ->count();
        $fri_rma = DB::table('boards')
            ->where(
                [
                    'endOfRepair' => $fri,
                    'status' => 2,
                ]
            )
            ->count();
        $sat_rma = DB::table('boards')
            ->where(
                [
                    'endOfRepair' => $sat,
                    'status' => 2,
                ]
            )
            ->count();
        $sun_rma = DB::table('boards')
            ->where(
                [
                    'endOfRepair' => $sun,
                    'status' => 2,
                ]
            )
            ->count();
        $query['mon_rma'] = ['date' => date("d", strtotime(date("Y-m-d",$monday))), 'mon_rma' => $mon_rma];
        $query['tue_rma'] = ['date' => date("d", strtotime(date("Y-m-d",$monday)." +1 days")), 'tue_rma' => $tue_rma];
        $query['wed_rma'] = ['date' => date("d", strtotime(date("Y-m-d",$monday)." +2 days")), 'wed_rma' => $wed_rma];
        $query['thu_rma'] = ['date' => date("d", strtotime(date("Y-m-d",$monday)." +3 days")), 'thu_rma' => $thu_rma];
        $query['fri_rma'] = ['date' => date("d", strtotime(date("Y-m-d",$monday)." +4 days")), 'fri_rma' => $fri_rma];
        $query['sat_rma'] = ['date' => date("d", strtotime(date("Y-m-d",$monday)." +5 days")), 'sat_rma' => $sat_rma];
        $query['sun_rma'] = ['date' => date("d", strtotime(date("Y-m-d",$monday)." +6 days")), 'sun_rma' => $sun_rma];

        return $query;
    }

}
