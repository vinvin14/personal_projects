<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Customer;
use App\Models\RepairRecords;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class RepairController extends Controller
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }

    public function index()
    {
        //$data = RepairRecords::orderBy('transactionDate', 'ASC')->paginate(10);
        $data = DB::table('repairrecords')
                        ->join('customers', 'customers.id', '=', 'repairrecords.customer')
                        ->where('softDeleted', '=', 'no')
                        ->select('repairrecords.*', 'customers.name as customer', 'customers.customerID')
                        ->paginate(10);
        return view('repair.index', ['data' => $data, 'sidebar_selected' => 'Repairs']);
    }
    public function show($id)
    {
        $repair = DB::table('repairrecords')
                        ->join('customers', 'customers.id', '=', 'repairrecords.customer')
                        ->where('repairrecords.id', $id)
                        ->select('repairrecords.*', 'customers.name as customer')
                        ->first();
        if(empty($repair))
        {
            return redirect(route('repairs'))->with('error', 'Repair record not found!');
        }
        $data['repair'] = $repair;
        $data['boards'] = DB::table('boards')
                                ->join('status', 'status.id', '=', 'boards.status')
                                ->select('boards.*', 'status.status as status')
                                ->where(['motherRecord' => $id, 'softDeleted' => 'no'])->get();
        return view('repair.show', ['data' => $data, 'sidebar_selected' => 'Repairs']);
    }
    public function create()
    {
        $data['repairs'] = DB::table('repairrecords')
                                ->join('customers', 'customers.id', '=', 'repairrecords.customer')
                                ->select('repairrecords.*', 'customers.customerID as customer')
                                ->where('softDeleted', 'no')
                                ->orderBy('transactionDate', 'ASC')
                                ->paginate(10);
        $data['customers'] = Customer::orderBy('name', 'ASC')->get();
        return view('repair.create', ['data' => $data, 'sidebar_selected' => 'Repairs']);
    }
    public function store(Request $request)
    {
        try
        {
            $input = $request->except('_token');
            $input['description'] = ucfirst(htmlentities($input['description']));
            $newRepair = DB::table('repairrecords')->insertGetId($input);
            return redirect(route('repair.show', $newRepair))->with('response', 'You have created new Repair Transaction! Start recording a board!');
        }
        catch (\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'REPAIRADD',
                $exception->getMessage()
            ]);
            return redirect(route('repairs'))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function update($id)
    {
        $repair = DB::table('repairrecords')
            ->where('repairrecords.id', $id)
            ->where('softDeleted', 'no')
            ->first();
        if(empty($repair))
        {
            return redirect(route('repairs'))->with('error', 'Repair record not found!');
        }
        $data['data'] = $repair;
        $data['customers'] = Customer::orderBy('name', 'ASC')->get();
        $data['status'] = Status::orderBy('status', 'ASC')->get();

        return view('repair.update', ['data' => $data, 'sidebar_selected' => 'Repairs']);
    }
    public function upsave($id, Request $request)
    {
        $repair = RepairRecords::find($id);
        $duplicate = RepairRecords::where([
            'description' => $request->input('description'),
            'customer' => $request->input('customer'),
            'transactionDate' => $request->input('transactionDate'),
        ])->first();
        if(! empty($duplicate))
        {
            if($repair->description != $request->input('description') && $repair->customer != $request->input('customer') && $repair->transactionDate != $request->input('transactionDate'))
            {
                return redirect(route('repair.show', $id))->with('error', 'Changes that you want to proceed will cause duplication!');
            }
        }
        if(empty($repair))
        {
            return redirect(route('repairs'))->with('error', 'Repair record not found!');
        }

        try
        {
            $repair->update($request->post());
            return redirect(route('repair.show', $id))->with('response', 'Repair records has been updated!');
        }
        catch (\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'REPAIRADD',
                $exception->getMessage()
            ]);
            return redirect(route('repairs'))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function updateRepairStatus($id)
    {
        $repaired = Board::where(['motherRecord' => $id, 'status' => 2])->count();
        $defective = Board::where(['motherRecord' => $id, 'status' => 4])->count();
        $forRepair = Board::where(['motherRecord' => $id, 'status' => 1])->count();
        $totalJob = $repaired+$defective+$forRepair;
        DB::table('repairrecords')
            ->where('id', $id)
            ->update([
                'totalRepaired' => $repaired,
                'totalDefective' => $defective,
                'boardsForRepair' => $forRepair,
                'totalJob' => $totalJob,
             ]);
    }
    public function softDestory($id)
    {
        $query = RepairRecords::findOrFail($id);
        $boards = Board::where('motherRecord', $id)->get();
        $query->update(['softDeleted' => 'yes']);
        foreach ($boards as $board)
            Board::where('id', $boards->id)->update(['softDeleted' => 'yes']);

        return redirect(route('repairs'))->with('response', 'Record has been deleted!');
    }
    public function search(Request $request)
    {
        switch ($request->get('search_by')) {
            case 'customer':
                $customer_id = [];
                $customer_query = DB::table('customers')
                                        ->orWhere('customerID', 'LIKE', '%'.$request->get('keyword').'%')
                                        ->orWhere('name', 'LIKE', '%'.$request->get('keyword').'%')
                                        ->select('id')
                                        ->get();
                foreach ($customer_query as $id) {
                    array_push($customer_id, $id->id);
                }
                $results = DB::table('repairrecords')
                                    ->leftJoin('customers', 'customers.id', '=', 'repairrecords.customer')
                                    ->select(
                                        'repairrecords.*',
                                        'customers.customerID',
                                        'customers.name',
                                    )
                                    ->whereIn('customer', $customer_id)
                                    ->where('softDeleted', '=', 'no')
                                    ->paginate(5);
                return view('repair.search_results', ['data' => $results, 'sidebar_selected' => 'Repairs']);
                break;
            case 'description':
                $results = DB::table('repairrecords')
                                    ->leftJoin('customers', 'customers.id', '=', 'repairrecords.customer')
                                    ->select(
                                        'repairrecords.*',
                                        'customers.customerID',
                                        'customers.name',
                                        )
                                    ->where('description', 'LIKE', '%'.$request->get('keyword').'%')
                                    ->where('softDeleted', '=', 'no')
                                    ->paginate(10);
                return view('repair.search_results', ['data' => $results, 'sidebar_selected' => 'Repairs']);
                break;
            case 'rma':
                $results = DB::table('boards')
                                ->leftJoin('repairrecords', 'boards.motherRecord', '=', 'repairrecords.id')
                                ->leftJoin('customers', 'customers.id', '=', 'repairrecords.customer')
                                ->select(
                                    'repairrecords.*',
                                    'customers.customerID',
                                    'customers.name',
                                )
                                ->where('boards.rma', 'LIKE', '%'.$request->get('keyword').'%')
                                ->where('repairrecords.softDeleted', '=', 'no')
                                ->paginate(10);
                return view('repair.search_results', ['data' => $results, 'sidebar_selected' => 'Repairs']);
                break;
        }

    }
}
