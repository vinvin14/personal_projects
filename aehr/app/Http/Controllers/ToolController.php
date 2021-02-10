<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class ToolController extends Controller
{
    private $util, $notification;
    public function __construct()
    {
        $this->util = new UtilitiesController();
        $this->notification = new NotificationController();
    }

    public function index()
    {
        $query = DB::table('tools')
                        ->leftJoin('units', 'units.id', '=', 'tools.unit')
                        ->select('tools.*', 'units.unit as unitName')
                        ->selectRaw('SUM(tools.actualQuantity) as totalQuantity, SUM(tools.unitPrice) as totalPrices')
                        ->orderBy('tools.description', 'ASC')
                        ->groupBy('partNumber', 'description')
                        ->where('is_out' ,'!=', 1)
                        ->where('deleted_at' ,'=', null)
                        ->paginate(50);

        if(empty($query->toArray()))
        {
            return view('resources.tool.index', ['error' => 'No Record(s) Found!', 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'tools']);
        }
        return view('resources.tool.index', ['data' => $query, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'tools']);
    }
    public function show($id)
    {
        $query['data'] = DB::table('tools')
            ->leftJoin('units', 'units.id', '=', 'tools.unit')
            ->leftJoin('locations', 'locations.id', '=', 'tools.storedIn')
            ->select(
                'tools.*',
                'units.id as unitID',
                'units.unit as unit',
                'locations.id as storedInID',
                'locations.location as storedIn'
            )
            ->where('tools.id', $id)
            ->first();
        $query['incoming'] = DB::table('movement_tool')
            ->leftJoin('tools', 'tools.id', '=', 'movement_tool.reference')
            ->select(
                'movement_tool.*',
                'tools.invoice_number',
                'tools.vendor',
                'tools.brand',
            )
            ->where([
                'reference' => $id,
                'type' => 'incoming',
                'movement_tool.deleted_at' => null
            ])
            ->paginate(10);
        $query['outgoing'] = DB::table('movement_tool')
            ->leftJoin('purposes', 'purposes.id', '=', 'movement_tool.purpose')
            ->where([
                'reference' => $id,
                'type' => 'outgoing',
                'movement_tool.deleted_at' => null
            ])
            ->select(
                'movement_tool.*',
                'purposes.purpose as purposeName',

                )
            ->paginate(10);
        if(empty($query))
        {
            return redirect(route('tools'))->with('error', 'Tool record not found!');
        }
        return view('resources.tool.show', ['data' => $query, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'tools']);
    }
    public function showCollected($partNumber)
    {
        $query['partNumber'] = $partNumber;
        $query['data'] = DB::table('tools')
                            ->leftJoin('units', 'units.id', '=', 'tools.unit')
                            ->leftJoin('locations', 'locations.id', '=', 'tools.storedIn')
                            ->select('tools.*', 'units.id as unitID', 'units.unit as unit', 'locations.id as storedInID', 'locations.location as storedIn')
                            ->where(['tools.partNumber' => $partNumber, 'is_out' => 0, 'deleted_at' => null])
                            ->get();

        return view('resources.tool.show-collection', ['data' => $query, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'tools']);
    }
    public function create()
    {
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['units'] = Unit::orderBy('unit', 'ASC')->get();
        $data['tools'] = Tool::where(['is_out' => 0, 'deleted_at' => null])->orderBy('description', 'ASC')->get();
        return view('resources.tool.create', ['data' => $data, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'tools']);
    }
    public function store(Request $request)
    {
        $input = $request->except('_token');
        $duplicate = Tool::where([
            'description' => $input['description'],
            'partNumber' => $input['partNumber'],
            'modelNumber' => $input['modelNumber'],
        ])->first();
        if(! empty($duplicate))
        {
            return redirect(route('tool.create'))->with('error',  'This entry has been added before and might cause duplication.');
        }
        DB::beginTransaction();
        try
        {
            $input['description'] = ucfirst($this->util->cleanExSpace($input['description']));
            $input['partNumber'] = strtoupper($this->util->cleanExHyphen($input['partNumber']));
            $input['dateAdded'] = date('Y-m-d');
            $input['entryBy'] = $_SESSION['user'];
            $input['totalPrice'] = intval($input['actualQuantity']) * doubleval($input['unitPrice']);
            DB::table('tools')->insert($input);
            DB::commit();
            return redirect(route('tool.create'))->with('response', 'You have successfully created new Tool!');
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            $this->util->createError([
                $_SESSION['user'],
                'TOOLADD',
                $exception->getMessage()
            ]);
            return redirect(route('tool.create'))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function update($id)
    {
        $query = DB::table('tools')
            ->leftJoin('units', 'units.id', '=', 'tools.unit')
            ->leftJoin('locations', 'locations.id', '=', 'tools.location')
            ->select('tools.*', 'units.id as unitID', 'units.unit as unit', 'locations.id as storedInID', 'locations.location as storedIn')
            ->where('tools.id', $id)
            ->first();
        $data['tools'] = $query;
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['units'] = Unit::orderBy('unit', 'ASC')->get();
        $data['has_movement'] = DB::table('movement_tool')->where('reference', $id)->get()->toArray();
        if(empty($query))
        {
            return redirect(route('tools'))->with('error', 'Equipment record not found!');
        }
        return view('resources.tool.update', ['data' => $data, 'sidebar_selected' => 'Resources', 'reference_nav_selected' => 'tools']);
    }
    public function upsave($id, Request $request)
    {
        $query = Tool::find($id);
        $duplicate = Tool::where([
            'description' => $request->input('description'),
            'partNumber' => $request->input('partNumber'),
            'modelNumber' => $request->input('modelNumber')
        ])->first();

        if(! empty($duplicate))
        {
            if($query->description != $request->input('description') && $query->partNumber != $request->input('partNumber') && $query->modelNumber != $request->input('modelNumber'))
            {
                return redirect(route('tool.update', $id))->with('error', 'The entry that you are trying to save might cause a duplication!');
            }
        }
        if(empty($query))
        {
            return redirect(route('tool.update', $id))->with('error', 'Tool record not found!');
        }
        try
        {
            $query->update($request->post());
            $freshQuery = $query->fresh();
            if($freshQuery->actualQuantity <= $freshQuery->minimumQuantity)
            {
                $freshQuery->update(['criticalTagging' => 'critical']);
                $this->notification->create($freshQuery->id, $freshQuery->partNumber, 'critical', $_SESSION['user'], 'tools', '/resources/tool/'.$id);
            }
            if($freshQuery->actualQuantity == 0)
            {
                $freshQuery->update(['criticalTagging' => 'depleted']);
                $this->notification->create($freshQuery->id, $freshQuery->partNumber, 'depleted', $_SESSION['user'], 'tools', '/resources/tool/'.$id);
            }
            return redirect(route('tool.show', $id))->with('response', 'You have successfully updated this Tool record!');
        }
        catch (\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'TOOLUPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('equipment.show', $id))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function destroy($id)
    {
        $location = Tool::find($id);
        if(empty($location))
        {
            return redirect(route('tools'))->with('error', 'No Record(s) Found!');
        }
        $location->delete();
        return redirect(route('tools'))->with('response', 'Tool record has been deleted!');
    }
    public function search(Request $request)
    {
        switch ($request->get('search_by')) {
            case 'storedIn':
                $results = DB::table('locations')
                    ->where('is_out' ,'!=', 1)
                    ->where('locations.location', 'LIKE', '%' . $request->get('keyword') . '%')
                    ->join('tools', 'tools.storedIn', '=', 'locations.id')
                    ->select(
                        'tools.*',
                        'locations.location as storedIn',
                        )
                    ->paginate(10);
                return view('resources.tool.search_results', ['data' => $results, 'total_results' => count($results), 'sidebar_selected' => 'Repairs', 'reference_nav_selected' => 'tools']);
                break;
            case 'partnumber_description':
                $results = DB::table('tools')
                    ->leftJoin('locations', 'locations.id', '=', 'tools.storedIn')
                    ->select(
                        'tools.*',
                        'locations.id as storedInID',
                        'locations.location as storedIn',
                        )
                    ->where('tools.is_out' ,'!=', 1)
                    ->where(function ($query) use ($request){
                        $query->orWhere('tools.description', 'LIKE', '%' . $request->get('keyword') . '%')
                              ->orWhere('tools.partNumber', 'LIKE', '%' . $request->get('keyword') . '%');
                    })
                    ->paginate(10);
                return view('resources.tool.search_results', ['data' => $results, 'total_results' => count($results), 'sidebar_selected' => 'Repairs', 'reference_nav_selected' => 'tools']);
                break;
        }
    }
}
