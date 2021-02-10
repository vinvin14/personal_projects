<?php

namespace App\Http\Controllers;

use App\Models\Boardtype;
use App\Models\Component;
use App\Models\SystemType;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class ComponentController extends Controller
{
    private $util, $notification;
    public function __construct()
    {
        $this->util = new UtilitiesController();
        $this->notification = new NotificationController();
    }

    public function index()
    {
        $query = DB::table('components')
            ->leftJoin('units', 'units.id', '=', 'components.unit')
            ->leftJoin('locations', 'locations.id', '=', 'components.storedIn')
            ->leftJoin('systemtypes', 'systemtypes.id', '=', 'components.systemType')
            ->leftJoin('boardtypes', 'boardtypes.id', '=', 'components.boardType')
            ->select('components.*', 'units.id as unitID', 'units.unit as unit', 'locations.id as storedInID', 'locations.location as storedIn',
                'systemtypes.id as systemTypeID', 'systemtypes.systemType as systemType', 'boardtypes.id as boardTypeID', 'boardtypes.boardType as boardType')
            ->orderBy('components.description', 'ASC')
            ->paginate(50);
        if(empty($query->toArray()))
        {
            return view('resources.component.index', ['error' => 'No Record(s) Found!', 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'components']);
        }
        return view('resources.component.index', ['data' => $query, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'components']);
    }
    public function show($id)
    {
        $query['data'] = DB::table('components')
            ->leftJoin('units', 'units.id', '=', 'components.unit')
            ->leftJoin('locations', 'locations.id', '=', 'components.storedIn')
            ->leftJoin('systemtypes', 'systemtypes.id', '=', 'components.systemType')
            ->leftJoin('boardtypes', 'boardtypes.id', '=', 'components.boardType')
            ->select('components.*', 'units.id as unitID', 'units.unit as unit', 'locations.id as storedInID', 'locations.location as storedIn',
                'systemtypes.id as systemTypeID', 'systemtypes.systemType as systemType', 'boardtypes.id as boardTypeID', 'boardtypes.boardType as boardType')
            ->where('components.id', $id)
            ->first();
        $query['incoming'] = DB::table('movement_components')
            ->where([
                'reference' => $id,
                'type' => 'incoming',
                'movement_components.deleted_at' => null
                ])
            ->paginate(10);
        $query['outgoing'] = DB::table('movement_components')
            ->leftJoin('purposes', 'purposes.id', '=', 'movement_components.purpose')
            ->where([
                'reference' => $id,
                'type' => 'outgoing',
                'movement_components.deleted_at' => null
                ])
            ->select('movement_components.*', 'purposes.purpose as purposeName')
            ->paginate(10);
        if(empty($query))
        {
            return redirect(route('components'))->with('error', 'Component record not found!');
        }
        return view('resources.component.show', ['data' => $query, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'components']);
    }
    public function create()
    {
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['units'] = Unit::orderBy('unit', 'ASC')->get();
        $data['systemTypes'] = SystemType::orderBy('systemType', 'ASC')->get();
        $data['boardTypes'] = Boardtype::orderBy('boardType', 'ASC')->get();
        $data['components'] = Component::orderBy('partNumber', 'ASC')->get();
        return view('resources.component.create', ['data' => $data, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'components']);
    }
    public function store(Request $request)
    {
        $input = $request->except('_token');
//        dd($input);
//        $duplicate = Component::where([
//            'description' => $input['description'],
//            'partNumber' => $input['partNumber'],
//        ])->first();
        if(! empty($duplicate))
        {
            return redirect(route('component.create'))->with('error',  'This entry has been added before and might cause duplication.');
        }
        DB::beginTransaction();
        try
        {
            $input['description'] = ucfirst($this->util->cleanExSpace($input['description']));
            $input['partNumber'] = strtoupper($this->util->cleanExHyphen($input['partNumber']));
            $input['dateAdded'] = date('Y-m-d');
            $input['entryBy'] = $_SESSION['user'];
            $input['totalPrice'] = intval($input['actualQuantity']) * doubleval($input['unitPrice']);
//                    Component::create($input);
            $id = DB::table('components')->insertGetId($input);
//            switch ($input['transaction'])
//            {
//                case 'Initial Balancing':
//                    unset($input['transaction']);
//                    unset($input['receivedBy']);
//                    unset($input['receivedBy']);
//                    unset($input['invoiceNumber']);
//                    $input['description'] = ucfirst($this->util->cleanExSpace($input['description']));
//                    $input['partNumber'] = strtoupper($this->util->cleanExHyphen($input['partNumber']));
//                    $input['dateAdded'] = date('Y-m-d');
//                    $input['entryBy'] = $_SESSION['user'];
//                    $input['totalPrice'] = intval($input['actualQuantity']) * doubleval($input['unitPrice']);
////                    Component::create($input);
//                    DB::table('components')->insert($input);
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
//                    $id = DB::table('components')->insertGetId($input);
//                    DB::table('movements')
//                        ->insert([
//                            'type' => 'incoming',
//                            'origin' => 'components',
//                            'invoiceNumber' => $invoiceNumber,
//                            'reference' => $id,
//                            'vendor' => $input['vendor'],
//                            'quantity' => $input['actualQuantity'],
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
            return redirect(route('component.show', $id))->with('response', 'You have successfully created new Component!');
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            $this->util->createError([
                $_SESSION['user'],
                'COMPADD',
                $exception->getMessage()
            ]);
            return redirect(route('component.create'))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function update($id)
    {
        $query = DB::table('components')
            ->leftJoin('units', 'units.id', '=', 'components.unit')
            ->leftJoin('locations', 'locations.id', '=', 'components.storedIn')
//            ->leftJoin('systemtypes', 'systemtypes.id', '=', 'components.systemType')
//            ->leftJoin('boardtypes', 'boardtypes.id', '=', 'components.boardType')
            ->select(
                'components.*',
                'units.id as unitID',
                'units.unit as unit',
                'locations.id as storedInID',
                'locations.location as storedIn',
//                'systemtypes.id as systemTypeID',
//                'systemtypes.systemType as systemType',
//                'boardtypes.id as boardTypeID',
//                'boardtypes.boardType as boardType'
            )
            ->where('components.id', $id)
            ->first();
        $data['components'] = $query;
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['units'] = Unit::orderBy('unit', 'ASC')->get();
//        $data['systemTypes'] = SystemType::orderBy('systemType', 'ASC')->get();
//        $data['boardTypes'] = Boardtype::orderBy('boardType', 'ASC')->get();
        $data['has_movement'] = DB::table('movement_components')->where('reference', $id)->get()->toArray();
        if(empty($query))
        {
            return redirect(route('components'))->with('error', 'Component record not found!');
        }
        return view('resources.component.update', ['data' => $data, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'components']);
    }
    public function upsave($id, Request $request)
    {
        $query = Component::find($id);
        $duplicate = Component::where([
            'description' => $request->input('description'),
            'partNumber' => $request->input('partNumber'),
            'systemType' => $request->input('systemType')
        ])->first();

        if(! empty($duplicate))
        {
            if($query->description != $request->input('description') && $query->partNumber != $request->input('partNumber') && $query->systemType != $request->input('systemType'))
            {
                return redirect(route('component.update', $id))->with('error', 'The entry that you are trying to save might cause a duplication!');
            }
        }
        if(empty($query))
        {
            return redirect(route('component.update', $id))->with('error', 'Component record not found!');
        }
        try
        {
            $query->update($request->post());
            $freshQuery = $query->fresh();
            if($freshQuery->actualQuantity <= $freshQuery->minimumQuantity)
            {
                $freshQuery->update(['criticalTagging' => 'critical']);
                $this->notification->create($freshQuery->id, $freshQuery->partNumber, 'critical', $_SESSION['user'], 'components', '/resources/component/'.$id);
            }
            if($freshQuery->actualQuantity == 0)
            {
                $freshQuery->update(['criticalTagging' => 'depleted']);
                $this->notification->create($freshQuery->id, $freshQuery->partNumber, 'depleted', $_SESSION['user'], 'components', '/resources/component/'.$id);
            }
            return redirect(route('component.show', $id))->with('response', 'You have successfully updated this Component record!');
        }
        catch (\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'COMPUPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('component.show', $id))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function destroy($id)
    {
        $location = Component::find($id);
        if(empty($location))
        {
            return redirect(route('components'))->with('error', 'No Record(s) Found!');
        }
        $location->delete();
        return redirect(route('components'))->with('response', 'Component record has been deleted!');
    }
    public function search(Request $request)
    {
        switch ($request->get('search_by')) {
            case 'storedIn':
                $results = DB::table('locations')
                    ->orWhere('locations.location', 'LIKE', '%'.$request->get('keyword').'%')
                    ->join('components', 'components.storedIn', '=', 'locations.id')
                    ->select('components.*', 'locations.location as storedIn_name')
                    ->paginate(10);
                return view('resources.component.search_results', ['data' => $results, 'total_results' => count($results), 'sidebar_selected' => 'Repairs', 'reference_nav_selected' => 'component']);
                break;
            case 'partnumber_description':
                $results = DB::table('components')
                    ->leftJoin('locations', 'locations.id', '=', 'components.storedIn')
                    ->orWhere('components.description', 'LIKE', '%'.$request->get('keyword').'%')
                    ->orWhere('components.partNumber', 'LIKE', '%'.$request->get('keyword').'%')
                    ->select(
                        'components.*',
                        'locations.location as storedIn_name',
                        )
                    ->paginate(10);
                return view('resources.component.search_results', ['data' => $results, 'total_results' => count($results), 'sidebar_selected' => 'Repairs', 'reference_nav_selected' => 'component']);
                break;
        }

    }
}
