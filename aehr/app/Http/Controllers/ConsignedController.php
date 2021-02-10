<?php

namespace App\Http\Controllers;

use App\Models\Consigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Boardtype;
use App\Models\SystemType;
use App\Models\Location;
use App\Models\Unit;

class ConsignedController extends Controller
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }

    public function index()
    {
        $query = DB::table('consignedspares')
                        ->leftJoin('units', 'units.id', '=', 'consignedspares.unit')
                        ->leftJoin('locations', 'locations.id', '=', 'consignedspares.storedIn')
                        ->leftJoin('systemtypes', 'systemtypes.id', '=', 'consignedspares.systemType')
                        ->leftJoin('boardtypes', 'boardtypes.id', '=', 'consignedspares.boardType')
                        ->select( 'consignedspares.description', 'consignedspares.partNumber', 'units.id as unitID', 'units.unit as unitName', 'locations.id as storedInID', 'locations.location as storedInName',
                            'systemtypes.id as systemTypeID', 'systemtypes.systemType as systemTypeName', 'boardtypes.id as boardTypeID', 'boardtypes.boardType as boardTypeName')
                        ->selectRaw('SUM(consignedspares.actualQuantity) as totalQuantity, SUM(consignedspares.unitPrice) as totalPrices')
                        ->groupBy('partNumber')
                        ->where(['is_out' => 0, 'deleted_at' => null])
                        ->paginate(15);
//        dd($query);
//        $query = DB::table('consignedspares')
//            ->join('units', 'units.id', '=', 'consignedspares.unit')
//            ->join('locations', 'locations.id', '=', 'consignedspares.storedIn')
//            ->join('systemtypes', 'systemtypes.id', '=', 'consignedspares.systemType')
//            ->join('boardtypes', 'boardtypes.id', '=', 'consignedspares.boardType')
//            ->select('consignedspares.*', 'units.id as unitID', 'units.unit as unit', 'locations.id as storedInID', 'locations.location as storedIn',
//                'systemtypes.id as systemTypeID', 'systemtypes.systemType as systemType', 'boardtypes.id as boardTypeID', 'boardtypes.boardType as boardType')
//            ->orderBy('consignedspares.description', 'ASC')
//            ->paginate(50);
        if(empty($query->toArray()))
        {
            return view('resources.consigned.index', ['error' => 'No Record(s) Found!', 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'consigned']);
        }
        return view('resources.consigned.index', ['data' => $query, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'consigned']);
    }
    public function showCollected($partNumber)
    {
        $query['partNumber'] = $partNumber;
        $query['data'] = DB::table('consignedspares')
                            ->leftJoin('units', 'units.id', '=', 'consignedspares.unit')
                            ->leftJoin('locations', 'locations.id', '=', 'consignedspares.storedIn')
                            ->leftJoin('systemtypes', 'systemtypes.id', '=', 'consignedspares.systemType')
                            ->leftJoin('boardtypes', 'boardtypes.id', '=', 'consignedspares.boardType')
                            ->select('consignedspares.*', 'units.id as unitID', 'units.unit as unit', 'locations.id as storedInID', 'locations.location as storedIn',
                                'systemtypes.id as systemTypeID', 'systemtypes.systemType as systemType', 'boardtypes.id as boardTypeID', 'boardtypes.boardType as boardType')
                            ->where('consignedspares.partNumber', $partNumber)
                            ->where(['is_out' => 0, 'deleted_at' => null])
                            ->get();

        return view('resources.consigned.show-collection', ['data' => $query, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'consigned']);
    }
    public function show($id)
    {
        $query['data'] = DB::table('consignedspares')
            ->leftJoin('units', 'units.id', '=', 'consignedspares.unit')
            ->leftJoin('locations', 'locations.id', '=', 'consignedspares.storedIn')
            ->leftJoin('systemtypes', 'systemtypes.id', '=', 'consignedspares.systemType')
            ->leftJoin('boardtypes', 'boardtypes.id', '=', 'consignedspares.boardType')
            ->select('consignedspares.*', 'units.id as unitID', 'units.unit as unit', 'locations.id as storedInID', 'locations.location as storedIn',
                'systemtypes.id as systemTypeID', 'systemtypes.systemType as systemType', 'boardtypes.id as boardTypeID', 'boardtypes.boardType as boardType')
            ->where('consignedspares.id', $id)
            ->first();
        $query['incoming'] = DB::table('movement_consigned')
            ->leftJoin('consignedspares', 'consignedspares.id', '=', 'movement_consigned.reference')
            ->where([
                'reference' => $id,
                'type' => 'incoming',
                'movement_consigned.deleted_at' => null
                ])
            ->select('movement_consigned.*', 'consignedspares.invoice_number', 'consignedspares.vendor')
            ->paginate(10);
        $query['outgoing'] = DB::table('movement_consigned')
            ->leftJoin('purposes', 'purposes.id', '=', 'movement_consigned.purpose')
            ->where([
                'reference' => $id,
                'type' => 'outgoing',
                'movement_consigned.deleted_at' => null
                ])
            ->select('movement_consigned.*', 'purposes.purpose as purposeName')
            ->paginate(10);
        if(empty($query))
        {
            return redirect(route('consigned'))->with('error', 'Consigned Spare record not found!');
        }
        return view('resources.consigned.show', ['data' => $query, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'consigned']);
    }
    public function create()
    {
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['units'] = Unit::orderBy('unit', 'ASC')->get();
        $data['systemTypes'] = SystemType::orderBy('systemType', 'ASC')->get();
        $data['boardTypes'] = Boardtype::orderBy('boardType', 'ASC')->get();
        $data['consigned'] = Consigned::where(['is_out' => 0, 'deleted_at' => null])->orderBy('description', 'ASC')->get();
        return view('resources.consigned.create', ['data' => $data, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'consigned']);
    }
    public function store(Request $request)
    {
        $input = $request->except('_token');
        $duplicate = Consigned::where([
            'description' => $input['description'],
            'partNumber' => $input['partNumber'],
            'serialNumber' => $input['serialNumber'],
        ])->first();
        if(! empty($duplicate))
        {
            return redirect(route('consigned.create'))->with('error',  'This entry has been added before and might cause duplication.');
        }
        DB::beginTransaction();
        try
        {
            $input['description'] = ucfirst($this->util->cleanExSpace($input['description']));
            $input['partNumber'] = strtoupper($this->util->cleanExHyphen($input['partNumber']));
            $input['dateAdded'] = date('Y-m-d');
            $input['entryBy'] = $_SESSION['user'];
            $input['actualQuantity'] = 1;
            $input['totalPrice'] = intval($input['actualQuantity']) * doubleval($input['unitPrice']);
            $id = DB::table('consignedspares')->insertGetId($input);
//            switch ($input['transaction'])
//            {
//                case 'Initial Balancing':
//                    unset($input['transaction']);
//                    unset($input['receivedBy']);
//                    unset($input['invoiceNumber']);
//                    $input['description'] = ucfirst($this->util->cleanExSpace($input['description']));
//                    $input['partNumber'] = strtoupper($this->util->cleanExHyphen($input['partNumber']));
//                    $input['dateAdded'] = date('Y-m-d');
//                    $input['entryBy'] = $_SESSION['user'];
//                    $input['totalPrice'] = intval($input['actualQuantity']) * doubleval($input['unitPrice']);
//                    DB::table('consignedspares')->insert($input);
//                    break;
//                case 'Incoming':
//                    unset($input['transaction']);
//                    $receivedBy = $input['receivedBy'];
//                    unset($input['receivedBy']);
//                    $invoiceNumber = $input['invoiceNumber'];
//                    unset($input['invoiceNumber']);
//                    $input['description'] = ucfirst($this->util->cleanExSpace($input['description']));
//                    $input['partNumber'] = strtoupper($this->util->cleanExHyphen($input['partNumber']));
//                    $input['dateAdded'] = date('Y-m-d');
//                    $input['entryBy'] = $_SESSION['user'];
//                    $input['totalPrice'] = intval($input['actualQuantity']) * doubleval($input['unitPrice']);
//                    $id = DB::table('consignedspares')->insertGetId($input);
//                    DB::table('movements')
//                        ->insert([
//                            'type' => 'incoming',
//                            'origin' => 'consigned',
//                            'invoiceNumber' => $invoiceNumber,
//                            'reference' => $id,
//                            'vendor' => $input['vendor'],
//                            'quantity' => $input['actualQuantity'],
//                            'systemType' => $input['systemType'],
//                            'boardType' => $input['boardType'],
//                            'unitPrice' => $input['unitPrice'],
//                            'dateReceived' => $input['dateReceived'],
//                            'month' => date('F', strtotime($input['dateReceived'])),
//                            'year' => date('Y', strtotime($input['dateReceived'])),
//                            'receivedBy' => $receivedBy,
//                            'remarks' => $input['remarks'],
//                            'storedIn' => $input['storedIn'],
//                            'location' => $input['location'],
//                            'unitPrice' => $input['unitPrice'],
//                            'totalPrice' => $input['totalPrice'],
//                        ]);
//                    break;
//            }
            DB::commit();
            return redirect(route('consigned.show', $id))->with('response', 'You have successfully created new Consigned Spare!');
        }
        catch (\Exception $exception)
        {
//            dd($exception->getMessage());
            DB::rollBack();
            $this->util->createError([
                $_SESSION['user'],
                'CONSADD',
                $exception->getMessage()
            ]);
            return redirect(route('consigned.create'))->with('response', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function update($id)
    {
        $query = DB::table('consignedspares')
            ->leftJoin('units', 'units.id', '=', 'consignedspares.unit')
            ->leftJoin('locations', 'locations.id', '=', 'consignedspares.storedIn')
            ->leftJoin('systemtypes', 'systemtypes.id', '=', 'consignedspares.systemType')
            ->leftJoin('boardtypes', 'boardtypes.id', '=', 'consignedspares.boardType')
            ->select('consignedspares.*', 'units.id as unitID', 'units.unit as unit', 'locations.id as storedInID', 'locations.location as storedIn',
                'systemtypes.id as systemTypeID', 'systemtypes.systemType as systemType', 'boardtypes.id as boardTypeID', 'boardtypes.boardType as boardType')
            ->where('consignedspares.id', $id)
            ->first();
        $data['consigned'] = $query;
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['units'] = Unit::orderBy('unit', 'ASC')->get();
        $data['systemTypes'] = SystemType::orderBy('systemType', 'ASC')->get();
        $data['boardTypes'] = Boardtype::orderBy('boardType', 'ASC')->get();
        $data['has_movement'] = DB::table('movement_consigned')->where('reference', $id)->get()->toArray();
        if(empty($query))
        {
            return redirect(route('consigned'))->with('error', 'Consigned Spare record not found!');
        }
        return view('resources.consigned.update', ['data' => $data, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'consigned']);
    }
    public function upsave($id, Request $request)
    {
        $query = Consigned::find($id);
        $duplicate = Consigned::where([
            'description' => $request->input('description'),
            'partNumber' => $request->input('partNumber'),
            'serialNumber' => $request->input('serialNumber')
        ])->first();

        if(! empty($duplicate))
        {
            if($query->description != $request->input('description') && $query->partNumber != $request->input('partNumber') && $query->serialNumber != $request->input('serialNumber'))
            {
                return redirect(route('consigned.update', $id))->with('error', 'The entry that you are trying to save might cause a duplication!');
            }
        }
        if(empty($query))
        {
            return redirect(route('consigned.update', $id))->with('error', 'Consigned Spare record not found!');
        }
        try
        {
            $query->update($request->post());
            return redirect(route('consigned.show', $id))->with('response', 'You have successfully updated this Consigned Spare record!');
        }
        catch (\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'CONSUPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('consigned.show', $id))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function destroy($id)
    {
        $location = Consigned::find($id);
        if(empty($location))
        {
            return redirect(route('consigned'))->with('error', 'No Record(s) Found!');
        }
        $location->delete();
        return redirect(route('consigned'))->with('response', 'Consigned Spare record has been deleted!');
    }
    public function search(Request $request)
    {
        switch ($request->get('search_by')) {
            case 'storedIn':
                $results = DB::table('locations')
                                    ->join('consignedspares', 'consignedspares.storedIn', '=', 'locations.id')
                                    ->leftJoin('systemtypes', 'systemtypes.id', '=', 'consignedspares.systemType')
                                    ->select(
                                        'consignedspares.*',
                                        'systemtypes.systemType',
                                        'locations.location as storedIn',
                                        )
                                    ->where('locations.location', 'LIKE', '%'.$request->get('keyword').'%')
                                    ->where('consignedspares.is_out', '!=', 1)
                                    ->paginate(10);
                return view('resources.consigned.search_results', ['data' => $results, 'total_results' => count($results), 'sidebar_selected' => 'Repairs', 'reference_nav_selected' => 'consigned']);
                break;
            case 'partnumber_description':
                $results = DB::table('consignedspares')
                    ->leftJoin('locations', 'locations.id', '=', 'consignedspares.storedIn')
                    ->leftJoin('systemtypes', 'systemtypes.id', '=', 'consignedspares.systemType')
                    ->select(
                        'consignedspares.*',
                        'locations.id as storedInID',
                        'locations.location as storedIn',
                        'systemtypes.id as systemTypeID',
                        'systemtypes.systemType as systemType',
                    )
                    ->where('is_out', '!=', 1)
                    ->where(function ($query) use ($request) {
                            $query->orWhere('consignedspares.description', 'LIKE', '%'.$request->get('keyword').'%')
                                  ->orWhere('consignedspares.partNumber', 'LIKE', '%'.$request->get('keyword').'%');
                    })
                    ->paginate(10);

                return view('resources.consigned.search_results', ['data' => $results, 'total_results' => count($results), 'sidebar_selected' => 'Repairs', 'reference_nav_selected' => 'consigned']);
                break;
        }

    }
}
