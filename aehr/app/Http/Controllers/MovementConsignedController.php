<?php

namespace App\Http\Controllers;

use App\Models\Consigned;
use App\Models\MovementConsigned;
use App\Models\SystemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Location;
use App\Models\Unit;
use App\Models\Purpose;

class MovementConsignedController extends Controller
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }
    public function index()
    {
        $data['incoming'] = DB::table('movement_consigned')
                                    ->leftJoin('purposes', 'purposes.id', '=', 'movement_consigned.purpose')
                                    ->leftJoin('consignedspares', 'consignedspares.id', '=', 'movement_consigned.reference')
                                    ->select('purposes.purpose as purposeName', 'consignedspares.*', 'movement_consigned.*', 'consignedspares.invoice_number as invoice_number')
                                    ->where('type', '=', 'incoming')
                                    ->where('movement_consigned.deleted_at', '=', null)
                                    ->paginate(15);
        $data['outgoing'] = DB::table('movement_consigned')
                                    ->leftJoin('purposes', 'purposes.id', '=', 'movement_consigned.purpose')
                                    ->leftJoin('consignedspares', 'consignedspares.id', '=', 'movement_consigned.reference')
                                    ->select(
                                        'purposes.purpose as purposeName',
                                                'consignedspares.*',
                                                'movement_consigned.*'
                                    )
                                    ->where('type', '=', 'outgoing')
                                    ->where('movement_consigned.deleted_at', '=', null)
                                    ->paginate(15);
        if(empty($data))
            return view('movement.consigned.index', ['data' => $data, 'title' => 'Item Movement (Consigned Spares)', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'consigned']);
        return view('movement.consigned.index', ['data' => $data, 'title' => 'Item Movement (Consigned Spares)', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'consigned']);
    }
    public function show($id)
    {
        $data = DB::table('movement_consigned')
                            ->leftJoin('consignedspares', 'consignedspares.id', '=', 'movement_consigned.reference')
                            ->leftJoin('purposes', 'purposes.id', '=', 'movement_consigned.purpose')
                            ->leftJoin('systemtypes', 'systemtypes.id', '=', 'consignedspares.systemType')
                            ->select(
                                        'systemtypes.systemType as systemTypeName',
                                        'consignedspares.description',
                                        'consignedspares.partNumber',
                                        'consignedspares.serialNumber',
                                        'consignedspares.unitPrice',
                                        'consignedspares.actualQuantity as quantity',
                                        'consignedspares.systemType',
                                        'consignedspares.invoice_number',
                                        'consignedspares.depreciationValue',
                                        'consignedspares.usefulLife',
                                        'consignedspares.vendor',
                                        'consignedspares.totalPrice',
                                        'movement_consigned.*',
                                        'purposes.purpose as purpose',
                            )
                            ->where('movement_consigned.id', $id)
                            ->first();
        return view('movement.consigned.show', ['data' => $data,  'title' => 'Item Movement', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'consigned']);
    }
    public function update($id)
    {
        $data['data'] = DB::table('movement_consigned')
                                ->leftJoin('consignedspares', 'consignedspares.id', '=', 'movement_consigned.reference')
//                                ->leftJoin('purposes', 'purposes.id', '=', 'movement_consigned.purpose')
//                                ->select('movement_consigned.*', 'purposes.purpose as purpose', 'consignedspares.description',
                                ->select('movement_consigned.*',
//                                            'consignedspares.consigned_id',
                                            'consignedspares.description',
                                            'consignedspares.partNumber',
                                            'consignedspares.serialNumber',
                                            'consignedspares.unitPrice',
                                            'consignedspares.actualQuantity as quantity',
                                            'consignedspares.systemType',
//                                            'consignedspares.invoice_number',
                                            'consignedspares.depreciationValue',
                                            'consignedspares.usefulLife',
                                            'consignedspares.vendor',
                                            'consignedspares.totalPrice')
                                ->where('movement_consigned.id', $id)
                                ->first();

        $data['consigned'] = Consigned::where('is_out' ,'!=', 1)->where('deleted_at', null)->orderBy('description', 'ASC')->get();
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['systemTypes'] = SystemType::orderBy('systemType', 'ASC')->get();
        $data['units'] = Unit::orderBy('unit', 'ASC')->get();
        $data['purposes'] = Purpose::orderBy('purpose', 'ASC')->get();
        return view('movement.consigned.update', ['data' => $data,  'title' => 'Item Movement', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'consigned']);
    }
    public function upsave($id, Request $request)
    {
        $movement = MovementConsigned::findOrFail($id);
        $input = $request->except('_token');
        switch (true)
        {
            case $input['reference'] != $movement->reference:
                $new_ref = DB::table('consignedspares')->find($input['reference']);
                if($new_ref->actualQuantity <= 0) {
                    return redirect(route('movement.consigned.show', $id))->with('error', "Request has been denied since $new_ref->description has been depleted!");
                };
                DB::beginTransaction();
                try {
                    //old reference
//                    Consigned::find($movement->reference)->update(['is_out' => 0]);
                    DB::table('consignedspares')->where('id', $movement->reference)->update(['is_out' => 0]);
                    //new reference
//                    Consigned::find($input['reference'])->update(['is_out' => 1]);
                    DB::table('consignedspares')->where('id', $input['reference'])->update(['is_out' => 1]);
                    unset($input['invoice_number']);
                    DB::table('movement_consigned')
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
                        'ITMVCONSUPDATE',
                        $exception->getMessage()
                    ]);
                    return redirect(route('movement.consigned.show', $id))->with('error', 'Oops something went wrong! Please contact your administrator.');
                }
                break;
            case $input['reference']  == $movement->reference:
                Consigned::find($movement->reference)->update($input);
                unset($input['quantity']);
                $movement->update($input);
                break;
        }
        return redirect(route('movement.consigned.show', $id))->with('response', 'Movement Successfully Updated!');
    }
    public function create()
    {
        $data['data'] = Consigned::where(['is_out' => 0, 'deleted_at' => null])->orderBy('description', 'ASC')->get();
        $data['systemTypes'] = SystemType::orderBy('systemType', 'ASC')->get();
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['units'] = Unit::orderBy('unit', 'ASC')->get();
        $data['purpose'] = Purpose::orderBy('purpose', 'ASC')->get();

        return view('movement.consigned.create', ['data' => $data, 'title' => 'Item Movement (Consigned Spare)', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'consigned']);
    }
    public function store(Request $request)
    {
        $input = $request->except('_token');
        switch ($input['type']) {
            case 'incoming':
                DB::beginTransaction();
                try {
                    $ref = DB::table('consignedspares')->insertGetId([
                        'partNumber' => $input['partNumber'],
                        'serialNumber' => $input['serialNumber'],
                        'description' => $input['description'],
                        'vendor' => $input['vendor'],
                        'systemType' => $input['systemType'],
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
                        'usefulLife' => $input['usefulLife']
                    ]);
                    $movement_id = DB::table('movement_consigned')->insertGetId([
                        'purpose' => ($input['purpose']) ? $input['purpose'] : null,
                        'invoice_number' => $input['invoice_number'],
                        'reference' => $ref,
                        'date_received_released' => $input['date_received_released'],
                        'remarks' => $input['remarks'],
                        'received_released_by' => $input['received_released_by'],
                    ]);
                    DB::commit();
                    return redirect(route('movement.consigned.show', $movement_id))->with('response', 'Movement Successfully Recorded!');
                } catch (\Exception $exception) {
                    DB::rollBack();
                    $this->util->createError([
                        $_SESSION['user'],
                        'ITMVCONSADD',
                        $exception->getMessage()
                    ]);
                    return redirect(route('movement.consigned'))->with('error', 'Oops something went wrong! Please contact your administrator.');
                }
                break;
            case 'outgoing':
                DB::beginTransaction();
                try {
                    $duplicate = DB::table('movement_consigned')->where(['reference' => $input['reference'], 'type' => $input['type'], 'date_received_released' => date('Y-m-d')])->first();
                    if(! empty($duplicate))
                        return redirect(route('movement.consigned.create'))->with('error', 'Request Denied! this movement has been recorded previously!');
                    $ref = Consigned::findOrFail($input['reference']);
                    if($ref->actualQuantity == 0) {
                        return redirect(route('movement.consigned.create'))->with('error', "Request Denied! $ref->description has been depleted!");
                    }
                    DB::table('movement_consigned')->insert([
                        'purpose' => $input['purpose'],
                        'reference' => $ref->id,
                        'date_received_released' => $input['date_received_released'],
                        'type' => 'outgoing',
                        'received_released_by' => $input['received_released_by'],
                        'remarks' => $input['remarks'],
                    ]);
                    $ref->update(['is_out' => 1]);
                    DB::commit();
                    return redirect(route('movement.consigned'))->with('response', 'Movement successfully created!');
                } catch (\Exception $exception) {
                    DB::rollBack();
                    $this->util->createError([
                        $_SESSION['user'],
                        'ITMVCONSADD',
                        $exception->getMessage()
                    ]);
                    return redirect(route('movement.consigned'))->with('error', 'Oops something went wrong! Please contact your administrator.');
                }
                break;
        }
    }
    public function revert($id)
    {
        $movement = MovementConsigned::findOrFail($id);
        $consigned = Consigned::find($movement->reference);
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
