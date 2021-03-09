<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Component;
use App\Models\FaultCode;
use App\Models\FSE;
use App\Models\Location;
use App\Models\RepairRecords;
use App\Models\Status;
use App\Models\SystemType;
use App\Models\TypeOfService;
use App\Services\ActivityLogServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BoardController extends Controller
{
    private $util, $repair, $notification;
    public function __construct()
    {
        $this->util = new UtilitiesController();
        $this->repair = new RepairController();
        $this->notification = new NotificationController();
    }

    public function create($motherRecord)
    {
        $data['systemTypes'] = SystemType::orderBy('systemType', 'ASC')->get();
        $data['typeOfServices'] = TypeOfService::orderBy('typeOfService', 'ASC')->get();
        $data['locations'] = Location::orderBy('location', 'ASC')->get();
        $data['faultCodes'] = FaultCode::orderBy('code', 'ASC')->get();
        $data['fse'] = FSE::orderBy('lastname', 'ASC')->get();
        $data['motherRecord'] = DB::table('repairrecords')
                                        ->join('customers', 'repairrecords.customer', '=', 'customers.id')
                                        ->select(
                                            'repairrecords.*',
                                            'customers.name as customer_name',
                                            'customers.address as customer_address'
                                            )
                                        ->where('repairrecords.id', $motherRecord)
                                        ->first();
        $data['status'] = DB::table('status')->orderBy('status', 'ASC')->get();
        return view('board.create', ['data' => $data, 'sidebar_selected' => 'Repairs', 'reference_nav_selected' => 'consumables']);
    }
    public function show($motherRecord, $id)
    {
        $data['data'] = DB::table('boards')
                        ->leftJoin('repairrecords', 'repairrecords.id', '=', 'boards.motherRecord')
                        ->leftJoin('locations', 'locations.id', '=', 'boards.location')
                        ->leftJoin('faultcodes', 'faultcodes.id', '=', 'boards.faultCode')
                        ->leftJoin('officer', 'officer.id', '=', 'boards.fse')
                        ->leftJoin('systemTypes', 'systemTypes.id', '=', 'boards.systemType')
                        ->leftJoin('typeOfServices', 'typeOfServices.id', '=', 'boards.typeOfService')
                        ->leftJoin('status', 'status.id', '=', 'boards.status')
                        ->where('boards.id', $id)
                        ->select('boards.*',
                            'repairrecords.description as motherRecordName',
                            'repairrecords.id as motherRecordID',
                            'locations.location as location',
                            'systemTypes.systemType as systemType',
                            'typeOfServices.typeOfService as typeOfService',
                            'status.status as status', 'faultcodes.code as faultCode',
                            'officer.firstname as firstname', 'officer.middlename as middlename', 'officer.lastname as lastname')
                        ->first();
        $data['components'] = Component::orderBy('description', 'ASC')->get();
        $data['replacements'] = DB::table('movements')
                                    ->join('components', 'components.id', '=', 'movements.reference')
                                    ->where([
                                        'movements.rma'=> $id,
                                        'type' => 'outgoing'
                                    ])
                                    ->select('movements.*', 'components.description as replacement', 'components.partNumber as replacementPartNumber')
                                    ->get();
        if(empty($data['data']))
        {
            return redirect(route('repair.show', $motherRecord))->with('error', 'Board Entry not found!');
        }
        return view('board.show', ['data' => $data,'sidebar_selected' => 'Repairs']);
    }
    public function update($motherRecord, $id)
    {
        $data['data'] = DB::table('boards')
            ->select('boards.*',)
            ->where('boards.id', $id)
            ->first();
        $data['components'] = Component::orderBy('description', 'ASC')->get();
        $data['locations'] = Location::orderBy('location', 'ASC')->get();
        $data['faultCodes'] = FaultCode::orderBy('code', 'ASC')->get();
        $data['status'] = Status::orderBy('status', 'ASC')->get();
        $data['systemTypes'] = SystemType::orderBy('systemType', 'ASC')->get();
        $data['typeOfServices'] = TypeOfService::orderBy('typeOfService', 'ASC')->get();
        $data['fse'] = FSE::orderBy('lastname', 'ASC')->get();
        $data['replacements'] = DB::table('movement_components')
            ->leftJoin('components', 'components.id', '=', 'movement_components.reference')
            ->where([
                'movement_components.rma'=> $id,
            ])
            ->where('components.actualQuantity', '!=', 0)
            ->select('movement_components.*', 'components.description as replacement', 'components.partNumber as replacementPartNumber')
            ->get();
        if(empty($data['data']))
        {
            return redirect(route('board.show', [$motherRecord, $id]))->with('error', 'Board Record not found!');
        }
//        dd($data);
        return view('board.update', ['data' => $data, 'sidebar_selected' => 'Repairs']);
    }
    public function upsave($motherRecord, $id, Request $request, ActivityLogServices $activity_log)
    {
        $input = $request->except('_token');
        $board = Board::find($id);
        $duplicate = Board::where([
            'rma' => $input['rma'],
            'serialNumber' => $input['serialNumber'],
        ])->first();

        if(! empty($duplicate))
        {
            if($board->rma != $input['rma'] && $board->serialNumber != $input['serialNumber'])
            {
                return redirect('board.update', [$motherRecord, $id])->with('error', 'The update that you want to save will cause duplication therefore it has been denied!');
            }
        }
        try
        {
//            DB::table('boards')
//                    ->where('id', $id)
//                    ->update($input);
            $board->update($input);
            $update_values = array();
            foreach ($board->getChanges() as $index => $changes) {
                array_push($update_values, "$index changed to: $changes");
            }
            $actions = "Updated to Description: $board->description | RMA: $board->rma";
            foreach ($update_values as $updates) {
                $actions .= $updates.', ';
            }

            $activity_log->create(
                [
                    'user' => $_SESSION['user'],
                    'actions' => $actions,
                    'module' => 'BOARD_UPDATE',
                    'ip_address' => $_SERVER['REMOTE_ADDR']
                ]
            );
            $this->repair->updateRepairStatus($motherRecord);
            return redirect(route('repair.show', $motherRecord))->with('response', 'Updated Board Record!');
        }
        catch (\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'BOARDUPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('repair.show', [$motherRecord, $id]))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function store($motherRecord, Request $request)
    {
        $motherReference = DB::table('repairrecords')->where('id', $motherRecord)->first();
//        dd($motherRecord);
        $duplicate = Board::where([
            'rma' => $request->input('rma'),
            'serialNumber' => $request->input('serialNumber'),
            'motherRecord' => $motherRecord,
            'softDeleted' => 'no'
        ])->first();
        if(! empty($duplicate))
        {
            return redirect(route('repair.show', $motherRecord))->with('error', 'Board Record has been added previously!');
        }
        DB::beginTransaction();
        try
        {
            $input = $request->except('_token');
//            if($motherReference->totalJob == $motherReference->totalBoardsRecorded)
//            {
//                return redirect(route('board.create', $motherRecord))->with('error', 'Transaction Denied! Number of boards should not be exceeding the total job(s)!');
//            }
            $input['motherRecord'] = $motherRecord;
            if(empty($input['status'])) $input['status'] = 1;
            DB::table('boards')
                ->insert($input);
            $this->repair->updateRepairStatus($motherRecord);
            DB::commit();
            return redirect(route('repair.show', $motherRecord))->with('response', 'You have recorded new Board!');
        }
        catch (\Exception $exception)
        {
//            dd($exception->getMessage());
            $this->util->createError([
                $_SESSION['user'],
                'BOARDADD',
                $exception->getMessage()
            ]);
            return redirect(route('repair.show', $motherRecord))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function replacements($board, Request $request)
    {
        $input = $request->except('_token');
        $reference = Component::query()->findOrFail($input['reference']);
        $motherRecord = $input['motherRecord'];
        $available_quantity = $reference->actualQuantity - $input['quantity'];
        if($reference->actualQuantity == 0) {
            return redirect(route('board.show',[$motherRecord, $board]))->with('error', 'Transaction Denied! Component record has been deleted!');
        }
        DB::beginTransaction();
        try {
            switch (true)
            {
                case $available_quantity > 0:
                    $message = "Outgoing for $reference->partNumber | $reference->description has been recorded!";
                    $deducted = $input['quantity'];
                    $request_quantity = $available_quantity;
                    break;
                case $available_quantity <= 0:
                    $remainder = abs($available_quantity);
                    $deducted = $reference->actualQuantity;
                    $request_quantity = 0;
                    $message = "Outgoing for $reference->partNumber | $reference->description has been recorded, but we cannot accommodate the remaining quantity of $remainder!";
                    break;
            }

            $movement_id = DB::table('movement_components')
                    ->insertGetId(
                        [
                            'type' => 'outgoing',
                            'purpose' => 5,
                            'rma' => $board,
                            'reference_designator' => $input['referenceDesignator'],
                            'quantity' => $deducted,
                            'date_received_released' => $input['dateReceived'],
                            'reference' => $reference->id,
                            'received_released_by' => $input['receivedBy']
                        ]
                    );
            DB::table('movement_component_tracker')
                ->insert(
                    [
                        'type' => 'outgoing',
                        'rma' => $board,
                        'reference_designator' => $input['referenceDesignator'],
                        'component' => $reference->id,
                        'item_movement' => $movement_id,
                        'previous_quantity' => $reference->actualQuantity,
                        'new_quantity' => $request_quantity,
                        'entry_by' => $_SESSION['user'],
                        'date_of_transaction' => date('Y-m-d')

                    ]
                );
            DB::table('components')
                ->where('id', $reference->id)
                ->update(
                    [
                        'actualQuantity' => $request_quantity,
                        'totalPrice' => $reference->unitPrice * $request_quantity
                    ]
                );
            DB::commit();
            return redirect(route('board.update', [$motherRecord, $board]))->with('response', $message);
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->util->createError([
                $_SESSION['user'],
                'COMPMOVEMENTREPLACEMENTADD',
                $exception->getMessage()
            ]);
            return redirect(route('board.show', [$motherRecord, $board]))->with('error', 'Oops something went wrong! Please contact your administrator.');

        }
    }
    public function createMovementTracker($id, $reference, $prevQuantity, $updateQuantity, $type, $entryBy, $originGroup, $prevPrice, $updatePrice)
    {
        DB::table('movementstracker')
            ->insert(
                [
                    'movementRef' => $id,
                    'itemRef' => $reference,
                    'previousQuantity' => $prevQuantity,
                    'updateQuantity' => $updateQuantity,
                    'recordDate' => date('Y-m-d'),
                    'entryBy' => $entryBy,
                    'originGroup' => $originGroup,
                    'movementType' => $type,
                    'prevTotalPrice' => $prevPrice,
                    'updateTotalPrice' => $updatePrice
                ]
            );
    }
    public function print_rma($mother_record, $id)
    {
        $data['rma'] = DB::table('boards')
                            ->leftJoin('repairrecords', 'repairrecords.id', '=', 'boards.motherRecord')
                            ->leftJoin('customers', 'customers.id', '=', 'repairrecords.customer')
                            ->leftJoin('faultcodes', 'faultcodes.id', '=', 'boards.faultCode')
                            ->leftJoin('systemtypes', 'systemtypes.id', '=', 'boards.systemType')
                            ->leftJoin('typeofservices', 'typeofservices.id', '=', 'boards.typeOfService')
                            ->select(
                                'boards.*',
                                'customers.name'
                            )
                            ->where('boards.id', $id)
                            ->first();
        $data['replacements'] = DB::table('movement_components')
                                        ->leftJoin('components', 'components.id', '=', 'movement_components.reference')
                                        ->leftJoin('boards', 'boards.id', '=', 'movement_components.rma')
                                        ->leftJoin('faultcodes', 'faultcodes.id', '=', 'boards.faultCode')
                                        ->leftJoin('systemtypes', 'systemtypes.id', '=', 'boards.systemType')
                                        ->leftJoin('locations', 'locations.id', '=', 'components.storedIn')
                                        ->select(
                                            'movement_components.*',
                                            'boards.rma as rma',
                                            'boards.description',
                                            'boards.partNumber',
                                            'boards.serialNumber',
                                            'boards.testTime',
                                            'boards.repairTime',
                                            'boards.upgradeTime',
                                            'boards.workPerformed',
                                            'systemtypes.systemType as systemType',
                                            'faultcodes.code as faultcode',
                                            'components.partNumber',
                                            'components.description',
                                            'locations.location',
                                        )
                                        ->where('movement_components.rma',  $id)
                                        ->get();

        return view('board.print', ['data' => $data]);
    }
}
