<?php

namespace App\Http\Controllers;

use App\Models\Consumable;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class ConsumableController extends Controller
{
    private $util, $notification;
    public function __construct()
    {
        $this->util = new UtilitiesController();
        $this->notification = new NotificationController();
    }

    public function index()
    {
        $query = DB::table('consumables')
                        ->leftJoin('units', 'units.id', '=', 'consumables.unit')
                        ->leftJoin('locations', 'locations.id', '=', 'consumables.storedIn')
                        ->select('consumables.*', 'units.unit as unit', 'locations.location as storedIn')
                        ->orderBy('consumables.description', 'ASC')
                        ->paginate(50);
        if(empty($query->toArray()))
        {
            return view('resources.consumable.index', ['error' => 'No Record(s) Found!', 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'consumables']);
        }
//        dd($query);
        return view('resources.consumable.index', ['data' => $query, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'consumables']);
    }
    public function show($id)
    {
        $query['data'] = DB::table('consumables')
            ->join('units', 'units.id', '=', 'consumables.unit')
            ->join('locations', 'locations.id', '=', 'consumables.storedIn')
            ->select('consumables.*', 'units.id as unitID', 'units.unit as unit', 'locations.id as storedInID', 'locations.location as storedIn')
            ->where('consumables.id', $id)
            ->first();
        $query['incoming'] = DB::table('movement_consumables')
            ->where([
                'reference' => $id,
                'type' => 'incoming',
                'deleted_at' => null
                ])
            ->paginate(10);
        $query['outgoing'] = DB::table('movement_consumables')
            ->leftJoin('purposes', 'purposes.id', '=', 'movement_consumables.purpose')
            ->where([
                'reference' => $id,
                'type' => 'outgoing',
                'movement_consumables.deleted_at' => null
                ])
            ->select('movement_consumables.*', 'purposes.purpose as purposeName')
            ->paginate(10);
        if(empty($query['data']))
        {
            return redirect(route('consumables'))->with('error', 'Consumable record not found!');
        }
        return view('resources.consumable.show', ['data' => $query, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'consumables']);
    }
    public function create()
    {
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['unit'] = Unit::orderBy('unit', 'ASC')->get();
        $data['consumables'] = Consumable::orderBy('partNumber', 'ASC')->get();
        return view('resources.consumable.create', ['data' => $data, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'consumables']);
    }
    public function store(Request $request)
    {
        $input = $request->post();
        $duplicate = Consumable::where([
            'description' => $input['description'],
            'partNumber' => $input['partNumber'],
            'unitPrice' => $input['unitPrice']
        ])->first();
        if(! empty($duplicate))
        {
            return redirect(route('consumable.create'))->with('error',  'This entry has been added before and might cause duplication.');
        }
        try
        {
            $input['description'] = ucfirst($this->util->cleanExSpace($input['description']));
            $input['partNumber'] = strtoupper($this->util->cleanExHyphen($input['partNumber']));
            $input['dateAdded'] = date('Y-m-d');
            $input['entryBy'] = $_SESSION['user'];
            $input['totalPrice'] = $input['actualQuantity']* $input['unitPrice'];
            Consumable::create($input);
//            DB::table('consumables')->create($request->post());
            return redirect(route('consumable.create'))->with('response', 'You have successfully created new Consumable!');
        }
        catch (\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'CONSUMADD',
                $exception->getMessage()
            ]);
            return redirect(route('purposes'))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function update($id)
    {
        $query = DB::table('consumables')
            ->join('units', 'units.id', '=', 'consumables.unit')
            ->join('locations', 'locations.id', '=', 'consumables.storedIn')
            ->select('consumables.*', 'units.id as unitID', 'units.unit as unit', 'locations.id as storedInID', 'locations.location as storedIn')
            ->where('consumables.id', $id)
            ->first();
        $data['consumable'] = $query;
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['units'] = Unit::orderBy('unit', 'ASC')->get();
        $data['has_movement'] = DB::table('movement_consumables')->where('reference', $id)->get()->toArray();
        if(empty($query))
        {
            return redirect(route('consumables'))->with('error', 'Consumable record not found!');
        }
        return view('resources.consumable.update', ['data' => $data, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'consumables']);
    }
    public function upsave($id, Request $request)
    {
        $query = Consumable::find($id);
        $duplicate = Consumable::where([
            'description' => $request->input('description'),
            'partNumber' => $request->input('partNumber'),
        ])->first();
        if(! empty($duplicate))
        {
            if($query->description != $request->input('description') && $query->partNumber != $request->input('partNumber'))
            {
                return redirect(route('consumable.update', $id))->with('error', 'The entry that you are trying to save might cause a duplication!');
            }
        }
        if(empty($query))
        {
            return redirect(route('consumable.update', $id))->with('error', 'Consumable record not found!');
        }
        try
        {
            $query->update($request->post());
            $freshQuery = $query->fresh();
            if($freshQuery->actualQuantity <= $freshQuery->minimumQuantity)
            {
                $freshQuery->update(['criticalTagging' => 'critical']);
                $this->notification->create($freshQuery->id, $freshQuery->description, $freshQuery->partNumber, 'critical', $_SESSION['user'], 'consumables', '/resources/consumable/'.$id);
            }
            if($freshQuery->actualQuantity == 0)
            {
                $freshQuery->update(['criticalTagging' => 'depleted']);
                $this->notification->create($freshQuery->id, $freshQuery->description, $freshQuery->partNumber, 'depleted', $_SESSION['user'], 'consumables', '/resources/consumable/'.$id);
            }
            return redirect(route('consumable.show', $id))->with('response', 'You have successfully updated this Consumable record!');
        }
        catch (\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'CONSUMUPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('consumable.show', $id))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function destroy($id)
    {
        $location = Consumable::find($id);
        if(empty($location))
        {
            return redirect(route('consumables'))->with('error', 'No Record(s) Found!');
        }
        $location->delete();
        return redirect(route('consumables'))->with('response', 'Consumable record has been deleted!');
    }
    public function search(Request $request)
    {
        switch ($request->get('search_by')) {
            case 'storedIn':
                $results = DB::table('locations')
                                ->orWhere('locations.location', 'LIKE', '%'.$request->get('keyword').'%')
                                ->join('consumables', 'consumables.storedIn', '=', 'locations.id')
                                ->select('consumables.*', 'locations.location as storedIn_name')
                                ->paginate(10);
                return view('resources.consumable.search_results', ['data' => $results, 'total_results' => count($results), 'sidebar_selected' => 'Repairs', 'reference_nav_selected' => 'consumables']);
                break;
            case 'partnumber_description':
                $results = DB::table('consumables')
                                ->leftJoin('locations', 'locations.id', '=', 'consumables.storedIn')
                                ->orWhere('consumables.description', 'LIKE', '%'.$request->get('keyword').'%')
                                ->orWhere('consumables.partNumber', 'LIKE', '%'.$request->get('keyword').'%')
                                ->select(
                                    'consumables.*',
                                    'locations.location as storedIn_name',
                                    )
                                ->paginate(10);
                return view('resources.consumable.search_results', ['data' => $results, 'total_results' => count($results), 'sidebar_selected' => 'Repairs', 'reference_nav_selected' => 'consumables']);
                break;
        }

    }
}
