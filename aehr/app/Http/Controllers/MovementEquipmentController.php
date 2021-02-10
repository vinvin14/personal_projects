<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Location;
use App\Models\MovementEquipment;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Purpose;

class MovementEquipmentController extends Controller
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }

    public function index()
    {
        $data['incoming'] = DB::table('movement_equipment')
                                ->leftJoin('purposes', 'purposes.id', '=', 'movement_equipment.purpose')
                                ->leftJoin('equipment', 'equipment.id', '=', 'movement_equipment.reference')
                                ->select('purposes.purpose as purposeName', 'equipment.*', 'movement_equipment.*')
                                ->where('type', '=', 'incoming')
                                ->where('movement_equipment.deleted_at', '=', null)
                                ->paginate(15);

        $data['outgoing'] = DB::table('movement_equipment')
                                ->leftJoin('purposes', 'purposes.id', '=', 'movement_equipment.purpose')
                                ->leftJoin('equipment', 'equipment.id', '=', 'movement_equipment.reference')
                                ->select('purposes.purpose as purposeName', 'equipment.*', 'movement_equipment.*')
                                ->where('type', '=', 'outgoing')
                                ->where('movement_equipment.deleted_at', '=', null)
                                ->paginate(15);
//        if(empty($data))
//            return view('movement.equipment.index', ['data' => $data, 'title' => 'Equipment', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'equipment']);
        return view('movement.equipment.index', ['data' => $data, 'title' => 'Equipment', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'equipment']);
    }
    public function show($id)
    {
        $data = DB::table('movement_equipment')
                    ->leftJoin('equipment', 'equipment.id', '=', 'movement_equipment.reference')
                    ->leftJoin('purposes', 'purposes.id', '=', 'movement_equipment.purpose')
                    ->select('movement_equipment.*', 'purposes.purpose as purpose', 'equipment.description',
                        'equipment.actualQuantity as quantity', 'equipment.partNumber', 'equipment.serialNumber', 'equipment.modelNumber',
                        'equipment.brand', 'equipment.vendor', 'equipment.unitPrice', 'equipment.totalPrice', 'equipment.invoice_number')
                    ->where('movement_equipment.id', $id)
                    ->first();

        return view('movement.equipment.show', ['data' => $data, 'title' => 'Equipment', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'equipment']);
    }
    public function update($id)
    {
        $data['data'] = DB::table('movement_equipment')
                    ->leftJoin('equipment', 'equipment.id', '=', 'movement_equipment.reference')
                    ->select(
                        'movement_equipment.*',
                        'equipment.id as equipmentID',
                        'equipment.description',
                        'equipment.actualQuantity as quantity',
                        'equipment.partNumber',
                        'equipment.serialNumber',
                        'equipment.modelNumber',
                        'equipment.brand',
                        'equipment.vendor',
                        'equipment.unitPrice',
                        'equipment.totalPrice',
                        'equipment.invoice_number')
                    ->where('movement_equipment.id', $id)
                    ->first();
        $data['equipment'] = Equipment::where('is_out' ,'!=', 1)->orderBy('description', 'ASC')->get();
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['units'] = Unit::orderBy('unit', 'ASC')->get();
        $data['purposes'] = Purpose::orderBy('purpose', 'ASC')->get();
        return view('movement.equipment.update', ['data' => $data, 'title' => 'Equipment', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'equipment']);
    }
    public function upsave($id, Request $request)
    {
        $movement = MovementEquipment::findOrFail($id);
        $input = $request->except('_token');
        switch (true)
        {
            case $input['reference'] != $movement->reference:
                $new_ref = DB::table('equipment')->find($input['reference']);
                dd($new_ref);
                if($new_ref->actualQuantity <= 0) {
                    return redirect(route('movement.equipment.show', $id))->with('error', "Request has been denied since $new_ref->description has been depleted!");
                };
                DB::beginTransaction();
                try {
                    //old reference
                    //Consigned::find($movement->reference)->update(['is_out' => 0]);
                    DB::table('equipment')->where('id', $movement->reference)->update(['is_out' => 0]);
                    //new reference
                    //Consigned::find($input['reference'])->update(['is_out' => 1]);
                    DB::table('equipment')->where('id', $input['reference'])->update(['is_out' => 1]);
                    unset($input['invoice_number']);
                    DB::table('movement_equipment')
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
                        'ITMVEQUIPUPDATE',
                        $exception->getMessage()
                    ]);
                    return redirect(route('movement.equipment.show', $id))->with('error', 'Oops something went wrong! Please contact your administrator.');
                }
//                    //old reference
//                    Equipment::find($movement->reference)->update(['is_out' => 0]);
//                    //new reference
//                    Equipment::find($input['reference'])->update(['is_out' => 1]);
//
//                    $movement->update($input);
                break;
            case $input['reference']  == $movement->reference:
//                dd($input);
                    Equipment::find($movement->reference)->update($input);
                    unset($input['quantity']);

                    $movement->update(
                        [
                            'type' => $input['type'],
                            'purpose' => ($input['purpose']) ? $input['purpose'] : null,
                            'reference' => $input['reference'],
                            'date_received' => $input['dateReceived'],
                            'received_by' => $input['receivedBy'],
                            'remarks' => $input['remarks']
                        ]
                    );
                break;
        }
        return redirect(route('movement.equipment.show', $id))->with('response', 'Movement Successfully Updated!');
    }
    public function create()
    {
        $data['equipment'] = Equipment::where('is_out' ,'!=', 1)->where('deleted_at', null)->orderBy('description', 'ASC')->get();
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['units'] = Unit::orderBy('unit', 'ASC')->get();
        $data['purpose'] = Purpose::orderBy('purpose', 'ASC')->get();
        return view('movement.equipment.create', ['data' => $data, 'title' => 'Equipment', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'equipment']);
    }
    public function store(Request $request)
    {
        $input = $request->except('_token');
        switch ($input['type'])
        {
            case 'incoming':
                DB::beginTransaction();
                try
                {

//                    dd($input);
                    $ref = DB::table('equipment')->insertGetId([
                        'partNumber' => $input['partNumber'],
                        'serialNumber' => $input['serialNumber'],
                        'modelNumber' => $input['modelNumber'],
                        'invoice_number' => $input['invoice_number'],
                        'vendor' => $input['vendor'],
                        'brand' => $input['brand'],
                        'description' => $input['description'],
                        'actualQuantity' => 1,
                        'dateReceived' => $input['dateReceived'],
                        'remarks' => $input['remarks'],
                        'storedIn' => $input['storedIn'],
                        'location' => $input['location'],
                        'unit' => $input['unit'],
                        'dateAdded' => date('Y-m-d'),
                        'entryBy' => $_SESSION['user'],
                        'unitPrice' => $input['unitPrice'],
                        'totalPrice' => $input['unitPrice'],
                    ]);
                    $movement_id = DB::table('movement_equipment')->insertGetId([
                        'reference' => $ref,
                        'date_received' => $input['dateReceived'],
                        'received_by' => $input['receivedBy'],
                        'remarks' => $input['remarks'],
                        'type' => 'incoming',
                    ]);
                    DB::commit();
                    return redirect(route('movement.equipment.show', $movement_id))->with('response', 'Movement Successfully Recorded!');
                }
                catch (\Exception $exception)
                {
                    DB::rollBack();
                    $this->util->createError([
                        $_SESSION['user'],
                        'MOVEQADD',
                        $exception->getMessage()
                    ]);
                    return redirect(route('movement.equipment'))->with('error', 'Oops something went wrong! Please contact your administrator.');
                }
                break;
            case 'outgoing':
                DB::beginTransaction();
                try
                {
                    $duplicate = DB::table('movement_equipment')->where(['reference' => $input['reference'], 'type' => $input['type'], 'date_received' => date('Y-m-d')])->first();
                    if(! empty($duplicate))
                        return redirect(route('movement.equipment.create'))->with('error', 'Request Denied! this movement has been recorded previously!');
                    $ref = Equipment::findOrFail($input['reference']);
                    if($ref->actualQuantity == 0) {
                        return redirect(route('movement.equipment.create'))->with('error', 'Request Denied! Reference Equipment has been depleted!');
                    }
                    DB::table('movement_equipment')->insert([
//                        'invoice_number' => $ref->invoice_number,
                        'type' => 'outgoing',
                        'purpose' => $input['purpose'],
                        'reference' => $input['reference'],
                        'date_received' => date('Y-m-d'),
                        'received_by' => $input['receivedBy'],
                        'remarks' => $input['remarks'],
                    ]);
                    $ref->update(['is_out' => 1]);
                    DB::commit();
                    return redirect(route('movement.equipment'))->with('response', 'Movement successfully created!');
                }
                catch (\Exception $exception)
                {
                    DB::rollBack();
                    $this->util->createError([
                        $_SESSION['user'],
                        'COMPUPDATE',
                        $exception->getMessage()
                    ]);
                    return redirect(route('movement.equipment'))->with('error', 'Oops something went wrong! Please contact your administrator.');
                }
                break;
        }
    }
    public function revert($id)
    {
        $movement = MovementEquipment::findOrFail($id);
        $equipment = Equipment::find($movement->reference);
        switch ($movement->type)
        {
            case 'incoming':
                $movement->update(['deleted_at' => date('Y-m-d')]);
                $equipment->update(['deleted_at' => date('Y-m-d')]);
                break;
            case 'outgoing':
                $movement->update(['deleted_at' => date('Y-m-d')]);
                $equipment->update(['is_out' => 0]);
                break;
        }
        return redirect(route('movement.equipment'))->with('response', 'Movement Successfully Reverted!');
    }
}
