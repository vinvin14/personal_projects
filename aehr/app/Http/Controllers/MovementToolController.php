<?php

namespace App\Http\Controllers;

use App\Models\MovementTool;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Location;
use App\Models\Unit;
use App\Models\Purpose;


class MovementToolController extends Controller
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }
    public function index()
    {
        $data['incoming'] = DB::table('movement_tool')
                                ->leftJoin('purposes', 'purposes.id', '=', 'movement_tool.purpose')
                                ->leftJoin('tools', 'tools.id', '=', 'movement_tool.reference')
                                ->select(
                                    'purposes.purpose as purposeName',
                                    'tools.*',
                                    'movement_tool.*'
                                )
                                ->where('type', '=', 'incoming')
                                ->where('movement_tool.deleted_at', '=', null)
                                ->paginate(15);

        $data['outgoing'] = DB::table('movement_tool')
                                ->leftJoin('purposes', 'purposes.id', '=', 'movement_tool.purpose')
                                ->leftJoin('tools', 'tools.id', '=', 'movement_tool.reference')
                                ->select(
                                    'purposes.purpose as purposeName',
                                    'tools.*',
                                    'movement_tool.*'
                                )
                                ->where('type', '=', 'outgoing')
                                ->where('movement_tool.deleted_at', '=', null)
                                ->paginate(15);
        if(empty($data))
            return view('movement.tool.index', ['data' => $data, 'title' => 'Item Movement (Tools)', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'tools']);
        return view('movement.tool.index', ['data' => $data, 'title' => 'Item Movement (Tools)', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'tools']);
    }
    public function show($id)
    {
        $data = DB::table('movement_tool')
                    ->leftJoin('tools', 'tools.id', '=', 'movement_tool.reference')
                    ->leftJoin('purposes', 'purposes.id', '=', 'movement_tool.purpose')
                    ->select(
                            'movement_tool.*',
                            'purposes.purpose as purpose',
                            'tools.invoice_number',
                            'tools.partNumber',
                            'tools.modelNumber',
                            'tools.description',
                            'tools.brand',
                            'tools.vendor',
                            'tools.actualQuantity as quantity',
                            'tools.unitPrice',
                            'tools.calibrationReq',
                            'tools.calibrationDate',
                            'tools.depreciationValue',
                    )
                    ->where('movement_tool.id', $id)
                    ->first();
        return view('movement.tool.show', ['data' => $data,  'title' => 'Item Movement', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'tools']);
    }
    public function update($id)
    {
        $data['data'] = DB::table('movement_tool')
                                ->leftJoin('tools', 'tools.id', '=', 'movement_tool.reference')
                                ->select(
                                    'movement_tool.*',
                                    'tools.description',
                                    'tools.partNumber',
                                    'tools.modelNumber',
                                    'tools.unitPrice',
                                    'tools.actualQuantity as quantity',
                                    'tools.invoice_number',
                                    'tools.vendor',
                                    'tools.brand'
                                )
                                ->where('movement_tool.id', $id)
                                ->first();

        $data['tools'] = Tool::where('is_out' ,'!=', 1)->where('deleted_at', null)->orderBy('description', 'ASC')->get();
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['units'] = Unit::orderBy('unit', 'ASC')->get();
        $data['purposes'] = Purpose::orderBy('purpose', 'ASC')->get();
        return view('movement.tool.update', ['data' => $data,  'title' => 'Item Movement', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'tools']);
    }
    public function upsave($id, Request $request)
    {
        $movement = MovementTool::findOrFail($id);
        $input = $request->except('_token');
        switch (true)
        {
            case $input['reference'] != $movement->reference:
                $new_ref = DB::table('tools')->find($input['reference']);
                if($new_ref->actualQuantity <= 0) {
                    return redirect(route('movement.tool.show', $id))->with('error', "Request has been denied since $new_ref->description has been depleted!");
                };
                DB::beginTransaction();
                try {
                    //old reference
                    //Consigned::find($movement->reference)->update(['is_out' => 0]);
                    DB::table('tools')->where('id', $movement->reference)->update(['is_out' => 0]);
                    //new reference
                    //Consigned::find($input['reference'])->update(['is_out' => 1]);
                    DB::table('tools')->where('id', $input['reference'])->update(['is_out' => 1]);
                    unset($input['invoice_number']);
                    DB::table('movement_tool')
                        ->where('id', $id)->update(
                            [
                                'purpose' => $input['purpose'],
                                'reference' => $input['reference'],
                                'date_received_released' => $input['date_received_released'],
                                'received_released_by' => $input['received_released_by'],
                                'remarks' => $input['remarks'],
                            ]);
//                    $movement->update($input);
                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollBack();
                    $this->util->createError([
                        $_SESSION['user'],
                        'ITMVTOOLUPDATE',
                        $exception->getMessage()
                    ]);
                    return redirect(route('movement.tool.show', $id))->with('error', 'Oops something went wrong! Please contact your administrator.');
                }
                break;
            case $input['reference']  == $movement->reference:
                Tool::find($movement->reference)->update($input);
                $movement->update($input);
                break;
        }
        return redirect(route('movement.tool.show', $id))->with('response', 'Movement Successfully Updated!');
    }
    public function create()
    {
        $data['tools'] = Tool::where(['is_out' => 0, 'deleted_at' => null])->orderBy('description', 'ASC')->get();
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['units'] = Unit::orderBy('unit', 'ASC')->get();
        $data['purpose'] = Purpose::orderBy('purpose', 'ASC')->get();

        return view('movement.tool.create', ['data' => $data, 'title' => 'Item Movement (Consigned Spare)', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'tools']);
    }
    public function store(Request $request)
    {
        $input = $request->except('_token');
        switch ($input['type']) {
            case 'incoming':
                DB::beginTransaction();
                try {
                    $ref = DB::table('tools')->insertGetId([
                        'partNumber' => $input['partNumber'],
                        'modelNumber' => $input['modelNumber'],
                        'description' => $input['description'],
                        'brand' => $input['brand'],
                        'vendor' => $input['vendor'],
                        'storedIn' => $input['storedIn'],
                        'location'=> $input['location'],
                        'dateAdded' => date('Y-m-d'),
                        'dateReceived' => $input['date_received_released'],
                        'actualQuantity' => 1,
                        'remarks' => $input['remarks'],
                        'unit' => $input['unit'],
                        'unitPrice' => $input['unitPrice'],
                        'totalPrice' => $input['unitPrice'],
                        'invoice_number' => $input['invoice_number'],
                        'depreciationValue' => $input['depreciationValue'],
                        'usefulLife' => $input['usefulLife'],
                        'calibrationReq' => $input['calibrationReq'],
                        'calibrationDate' => $input['calibrationDate'],
                        'origin' => 'incoming',
                    ]);
                    $movement_id = DB::table('movement_tool')->insertGetId([
                        'purpose' => $input['purpose'],
                        'reference' => $ref,
                        'date_received_released' => $input['date_received_released'],
                        'received_released_by' => $input['received_released_by'],
                        'remarks' => $input['remarks'],
                    ]);
                    DB::commit();
                    return redirect(route('movement.tool.show', $movement_id))->with('response', 'Movement Successfully Recorded!');
                } catch (\Exception $exception) {
                    DB::rollBack();
                    $this->util->createError([
                        $_SESSION['user'],
                        'ITMVTOOLSADD',
                        $exception->getMessage()
                    ]);
                    return redirect(route('movement.tools'))->with('error', 'Oops something went wrong! Please contact your administrator.');
                }
                break;
            case 'outgoing':
                DB::beginTransaction();
                try {
                    $duplicate = DB::table('movement_tool')->where(['reference' => $input['reference'], 'type' => $input['type'], 'date_received_released' => date('Y-m-d')])->first();
                    if(! empty($duplicate))
                        return redirect(route('movement.tool.create'))->with('error', 'Request Denied! this movement has been recorded previously!');
                    $ref = Tool::findOrFail($input['reference']);
                    if($ref->actualQuantity == 0) {
                        return redirect(route('movement.tool.create'))->with('error', "Request Denied! $ref->description has been depleted!");
                    }
                    DB::table('movement_tool')->insert(
                        [
                            'purpose' => $input['purpose'],
                            'reference' => $ref->id,
                            'date_received_released' => $input['date_received_released'],
                            'type' => 'outgoing',
                            'received_released_by' => $input['received_released_by'],
                            'remarks' => $input['remarks'],
                        ]
                    );
                    $ref->update(['is_out' => 1]);
                    DB::commit();
                    return redirect(route('movement.tools'))->with('response', 'Movement successfully created!');
                } catch (\Exception $exception) {
                    DB::rollBack();
                    $this->util->createError([
                        $_SESSION['user'],
                        'ITMVTOOLSADD',
                        $exception->getMessage()
                    ]);
                    return redirect(route('movement.tools'))->with('error', 'Oops something went wrong! Please contact your administrator.');
                }
                break;
        }
    }
    public function revert($id)
    {
        $movement = MovementTool::findOrFail($id);
        $consigned = Tool::find($movement->reference);
        switch ($movement->type)
        {
            case 'incoming':
                $movement->update(['deleted_at' => date('Y-m-d')]);
                $consigned->update(['deleted_at' => date('Y-m-d')]);
                break;
            case 'outgoing':
                $movement->update(['deleted_at' => date('Y-m-d')]);
                $consigned->update(['is_out' => 0]);
                break;
        }
        return redirect(route('movement.consigned'))->with('response', 'Movement Successfully Reverted!');
    }
}
