<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class EquipmentController extends Controller
{
    private $util, $notification;
    public function __construct()
    {
        $this->util = new UtilitiesController();
        $this->notification = new NotificationController();
    }

    public function index()
    {
        $query = DB::table('equipment')
                        ->leftJoin('units', 'units.id', '=', 'equipment.unit')
                        ->leftJoin('locations', 'locations.id', '=', 'equipment.storedIn')
                        ->select('equipment.*', 'units.unit as unitName', 'locations.location as storedInName')
                        ->selectRaw('SUM(equipment.actualQuantity) as totalQuantity, SUM(equipment.unitPrice) as totalPrices')
                        ->groupBy('partNumber', 'description')
                        ->orderBy('equipment.description', 'ASC')
                        ->where('is_out' ,'!=', 1)
                        ->where('deleted_at' ,'=', null)
                        ->paginate(50);
        if(empty($query->toArray()))
        {
            return view('resources.equipment.index', ['error' => 'No Record(s) Found!', 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'equipment']);
        }
        return view('resources.equipment.index', ['data' => $query, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'equipment']);
    }
    public function show($id)
    {
        $query['data'] = DB::table('equipment')
            ->leftJoin('units', 'units.id', '=', 'equipment.unit')
            ->leftJoin('locations', 'locations.id', '=', 'equipment.storedIn')
            ->select('equipment.*', 'units.id as unitID', 'units.unit as unit', 'locations.id as storedInID', 'locations.location as storedIn')
            ->where('equipment.id', $id)
            ->first();
        $query['incoming'] = DB::table('movement_equipment')
            ->leftJoin('equipment', 'equipment.id', '=', 'movement_equipment.reference')
            ->where([
                'reference' => $id,
                'type' => 'incoming',
                'movement_equipment.deleted_at' => null
                ])
            ->select(
                'movement_equipment.*',
                'equipment.invoice_number',
                'equipment.vendor',
                'equipment.actualQuantity as quantity',
            )
            ->paginate(10);
        $query['outgoing'] = DB::table('movement_equipment')
            ->leftJoin('purposes', 'purposes.id', '=', 'movement_equipment.purpose')
            ->where([
                'reference' => $id,
                'type' => 'outgoing',
                'movement_equipment.deleted_at' => null
                ])
            ->select(
                'movement_equipment.*',
                'purposes.purpose as purposeName',

            )
            ->paginate(10);
        if(empty($query))
        {
            return redirect(route('equipment'))->with('error', 'Equipment record not found!');
        }
        return view('resources.equipment.show', ['data' => $query, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'equipment']);
    }
    public function showCollected($partNumber)
    {
        $query['partNumber'] = $partNumber;
        $query['data'] = DB::table('equipment')
            ->leftJoin('units', 'units.id', '=', 'equipment.unit')
            ->leftJoin('locations', 'locations.id', '=', 'equipment.storedIn')
            ->select('equipment.*', 'units.id as unitID', 'units.unit as unit', 'locations.id as storedInID', 'locations.location as storedIn')
            ->where(['equipment.partNumber' => $partNumber, 'is_out' => 0, 'deleted_at' => null])
            ->get();

        return view('resources.equipment.show-collection', ['data' => $query, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'equipment']);
    }
    public function create()
    {
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['units'] = Unit::orderBy('unit', 'ASC')->get();
        $data['equipment'] = Equipment::orderBy('partNumber', 'ASC')->where(['is_out' => 0, 'deleted_at' => null])->get();
        return view('resources.equipment.create', ['data' => $data, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'equipment']);
    }
    public function store(Request $request)
    {
        $input = $request->except('_token');
        $duplicate = Equipment::where([
            'description' => $input['description'],
            'partNumber' => $input['partNumber'],
            'serialNumber' => $input['serialNumber'],
        ])->first();
        if(! empty($duplicate))
        {
            return redirect(route('equipment.create'))->with('error',  'This entry has been added before and might cause duplication.');
        }
        DB::beginTransaction();
        try
        {
            unset($input['transaction']);
            unset($input['receivedBy']);
            unset($input['invoiceNumber']);
            $input['description'] = ucfirst($this->util->cleanExSpace($input['description']));
            $input['partNumber'] = strtoupper($this->util->cleanExHyphen($input['partNumber']));
            $input['dateAdded'] = date('Y-m-d');
            $input['entryBy'] = $_SESSION['user'];
            $input['totalPrice'] = intval($input['actualQuantity']) * doubleval($input['unitPrice']);
            DB::table('equipment')->insert($input);
            DB::commit();
            return redirect(route('equipment.create'))->with('response', 'You have successfully created new Equipment!');
        }
        catch (\Exception $exception)
        {
            dd($exception->getMessage());
            DB::rollBack();
            $this->util->createError([
                $_SESSION['user'],
                'EQUIPADD',
                $exception->getMessage()
            ]);
            return redirect(route('equipment.create'))->with('response', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function update($id)
    {
        $query = DB::table('equipment')
            ->join('units', 'units.id', '=', 'equipment.unit')
            ->join('locations', 'locations.id', '=', 'equipment.storedIn')
            ->select('equipment.*', 'units.id as unitID', 'units.unit as unit', 'locations.id as storedInID', 'locations.location as storedIn')
            ->where('equipment.id', $id)
            ->first();
        $data['equipment'] = $query;
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['units'] = Unit::orderBy('unit', 'ASC')->get();
        $data['has_movement'] = DB::table('movement_equipment')->where('reference', $id)->get()->toArray();
        if(empty($query))
        {
            return redirect(route('equipment'))->with('error', 'Equipment record not found!');
        }
        return view('resources.equipment.update', ['data' => $data, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'equipment']);
    }
    public function upsave($id, Request $request)
    {
        $query = Equipment::find($id);
        $duplicate = Equipment::where([
            'description' => $request->input('description'),
            'partNumber' => $request->input('partNumber'),
            'serialNumber' => $request->input('serialNumber')
        ])->first();

        if(! empty($duplicate))
        {
            if($query->description != $request->input('description') && $query->partNumber != $request->input('partNumber') && $query->serialNumber != $request->input('serialNumber'))
            {
                return redirect(route('equipment.update', $id))->with('error', 'The entry that you are trying to save might cause a duplication!');
            }
        }
        if(empty($query))
        {
            return redirect(route('equipment.update', $id))->with('error', 'Equipment record not found!');
        }
        try
        {
            $query->update($request->post());
            $freshQuery = $query->fresh();
            if($freshQuery->actualQuantity <= $freshQuery->minimumQuantity)
            {
                $freshQuery->update(['criticalTagging' => 'critical']);
                $this->notification->create($freshQuery->id, $freshQuery->partNumber, 'critical', $_SESSION['user'], 'equipment', '/resources/equipment/'.$id);
            }
            if($freshQuery->actualQuantity == 0)
            {
                $freshQuery->update(['criticalTagging' => 'depleted']);
                $this->notification->create($freshQuery->id, $freshQuery->partNumber, 'depleted', $_SESSION['user'], 'equipment', '/resources/equipment/'.$id);
            }
            return redirect(route('equipment.show', $id))->with('response', 'You have successfully updated this Equipment record!');
        }
        catch (\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'EQUIPUPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('equipment.show', $id))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function destroy($id)
    {
        $location = Equipment::find($id);
        if(empty($location))
        {
            return redirect(route('equipment'))->with('error', 'No Record(s) Found!');
        }
        $location->delete();
        return redirect(route('equipment'))->with('response', 'Equipment record has been deleted!');
    }
    public function search(Request $request)
    {
        switch ($request->get('search_by')) {
            case 'storedIn':
                $results = DB::table('locations')
                                    ->where('equipment.is_out' ,'!=', 1)
                                    ->where('locations.location', 'LIKE', '%'.$request->get('keyword').'%')
                                    ->join('equipment', 'equipment.storedIn', '=', 'locations.id')
                                    ->select(
                                        'equipment.*',
                                        'systemtypes.systemType',
                                        'locations.location as storedIn',
                                        )
                                    ->paginate(10);
                return view('resources.equipment.search_results', ['data' => $results, 'total_results' => count($results), 'sidebar_selected' => 'Repairs', 'reference_nav_selected' => 'equipment']);
                break;
            case 'partnumber_description':
                $results = DB::table('equipment')
                    ->leftJoin('locations', 'locations.id', '=', 'equipment.storedIn')
                    ->select(
                        'equipment.*',
                        'locations.id as storedInID',
                        'locations.location as storedIn',
                    )
                    ->where('equipment.is_out' ,'!=', 1)
                    ->where(function ($query) use ($request){
                        $query->orWhere('equipment.description', 'LIKE', '%' . $request->get('keyword') . '%')
                            ->orWhere('equipment.partNumber', 'LIKE', '%' . $request->get('keyword') . '%');
                    })
                    ->paginate(10);

                return view('resources.equipment.search_results', ['data' => $results, 'total_results' => count($results), 'sidebar_selected' => 'Repairs', 'reference_nav_selected' => 'equipment']);
                break;
        }

    }
}
