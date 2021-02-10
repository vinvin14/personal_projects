<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Component;
use App\Models\MovementComponent;
use App\Models\MovementComponentTracker;
use App\Models\Purpose;
use App\Models\Unit;
use App\Models\Location;
use App\Services\ComponentServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MovementComponentController extends Controller
{
    private $request, $util, $notification;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->util = new UtilitiesController();
        $this->notification = new NotificationController();
    }
    public function index()
    {
        $data['incoming'] = DB::table('movement_components')
            ->leftJoin('components', 'components.id', '=', 'movement_components.reference')
            ->where([
                'type' => 'incoming',
                'deleted_at' => null,
            ])
            ->select(
                'movement_components.*',
                'components.description',
                'components.partNumber',
                'components.actualQuantity')
            ->paginate(10);

        $data['outgoing'] = DB::table('movement_components')
            ->leftJoin('components', 'components.id', '=', 'movement_components.reference')
            ->leftJoin('purposes', 'purposes.id', '=', 'movement_components.purpose')
            ->where([
                'type' => 'outgoing',
                'deleted_at' => null,
            ])
            ->select(
                'movement_components.*',
                'components.description',
                'components.partNumber',
                'components.actualQuantity',
                'purposes.purpose as purposeName',
                )
            ->paginate(10);

        return view('movement.components.index', ['data' => $data, 'title' => 'Item Movement (Components)', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'components']);
    }
    public function show($id)
    {
        $data = DB::table('movement_components')
            ->leftJoin('boards', 'boards.id', '=', 'movement_components.rma')
            ->leftJoin('components', 'components.id', '=', 'movement_components.reference')
            ->leftJoin('purposes', 'purposes.id', '=', 'movement_components.purpose')
            ->where('movement_components.id', $id)
            ->select(
                'movement_components.*',
                'components.partNumber',
                'components.description',
                'components.unitPrice',
                'purposes.purpose as purpose',
                'boards.rma as rma'
                )
            ->first();
        return view('movement.components.show', ['data' => $data, 'title' => 'Item Movement (Components)', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'components']);
    }

    public function create()
    {
        $data['data'] = Component::query()
//                                        ->where(['is_out' => 0, 'deleted_at' => null])
            ->orderBy('description', 'ASC')
            ->get();
        $data['boards'] = Board::where('status', 1)->orderBy('rma', 'ASC')->get();
        $data['purpose'] = Purpose::orderBy('purpose', 'ASC')->get();
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['unit'] = Unit::orderBy('unit', 'ASC')->get();

        return view('movement.components.create', ['data' => $data, 'title' => 'Item Movement (Components)', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'components']);
    }
    public function store(ComponentServices $componentServices)
    {
        $input = $this->request->except('_token');
        switch ($input['type'])
        {
            case 'incoming':
                switch ($input['reference_type'])
                {
                    case 'existing':
                        DB::beginTransaction();
                        try {
                            $reference = Component::findOrFail($input['reference']);
                            $item_movement_id = DB::table('movement_components')->insertGetId(
                                [
                                    'type' => $input['type'],
                                    'invoice_number' => $input['invoice_number'],
                                    'vendor' => ($input['vendor']) ? $input['vendor'] : null,
                                    'brand' => ($input['brand']) ? $input['brand'] : null,
                                    'reference' => $reference->id,
                                    'quantity' => $input['quantity'],
                                    'date_received_released' => $input['date_received_released'],
                                    'received_released_by' => $input['received_released_by'],
                                    'remarks' => $input['remarks']
                                ]
                            );
                            $this->create_tracker([
                                'type' => $input['type'],
                                'item_movement' => $item_movement_id,
                                'component' => $input['reference'],
                                'previous_quantity' => $reference->actualQuantity,
                                'new_quantity' => ($reference->actualQuantity + $input['quantity'] ),
//                                'previous_unit_price' => $reference->unitPrice,
//                                'new_unit_price' => ($reference->unitPrice * $input['quantity']),
                                'date_of_transaction' => date('Y-m-d'),
                                'entry_by' => $_SESSION['user']
                            ]);
                            $reference->update(['actualQuantity' => ($reference->actualQuantity + $input['quantity'])]);
                            DB::commit();
                            return redirect(route('movement.component.show', $item_movement_id))->with('response', "Incoming for $reference->partNumber | $reference->description has been recorded!");
                        } catch (\Exception $exception) {
                            DB::rollBack();
                            $this->util->createError([
                                $_SESSION['user'],
                                'MOVCOMPADD',
                                $exception->getMessage()
                            ]);
                            return redirect(route('movement.component.create'))->with('error', 'Oops something went wrong! Please contact your administrator.');
                        }
                        break;
                    case 'new':
                        DB::beginTransaction();
                        if($componentServices->ifExist($input)) {
                            return redirect(route('movement.component.create'))->with('error', 'The new Component record that you are entering has already been recorded previously!');
                        }
                        try {
                            $reference_id = DB::table('components')
                                ->insertGetId(
                                    [
                                        'partNumber' => $input['partNumber'],
                                        'description' => $input['description'],
                                        'storedIn' => $input['storedIn'],
                                        'location' => $input['location'],
                                        'dateAdded' => date('Y-m-d'),
                                        'actualQuantity' => $input['quantity'],
                                        'minimumQuantity' => ($input['minimumQuantity']) ? $input['minimumQuantity'] : 0,
                                        'maximumQuantity' => ($input['maximumQuantity']) ? $input['maximumQuantity'] : 0,
                                        'unitPrice' => $input['unitPrice'],
                                        'unit' => $input['unit'],
                                        'totalPrice' => ($input['unitPrice'] * $input['quantity']),
                                        'entryBy' => $_SESSION['user']
                                    ]
                                );
                            $reference = DB::table('components')->find($reference_id);
                            $item_movement_id = DB::table('movement_components')->insertGetId(
                                [
                                    'type' => $input['type'],
                                    'invoice_number' => $input['invoice_number'],
                                    'vendor' => ($input['vendor']) ? $input['vendor'] : null,
                                    'brand' => ($input['brand']) ? $input['brand'] : null,
                                    'reference' => $reference->id,
                                    'quantity' => $input['quantity'],
                                    'date_received_released' => $input['date_received_released'],
                                    'received_released_by' => $input['received_released_by'],
                                    'remarks' => $input['remarks']
                                ]
                            );
                            $this->create_tracker([
                                'type' => $input['type'],
                                'item_movement' => $item_movement_id,
                                'component' => $reference_id,
                                'previous_quantity' => 0,
                                'new_quantity' => $input['quantity'],
                                'previous_unit_price' => 0,
                                'new_unit_price' => $reference->unitPrice ,
                                'date_of_transaction' => date('Y-m-d'),
                                'entry_by' => $_SESSION['user']
                            ]);
                            DB::commit();
                            return redirect(route('movement.component.show', $item_movement_id))->with('response', "Incoming for $reference->partNumber | $reference->description has been recorded!");
                        }
                        catch (\Exception $exception) {
                            DB::rollBack();
                            $this->util->createError([
                                $_SESSION['user'],
                                'MOVCOMPADD',
                                $exception->getMessage()
                            ]);
                            return redirect(route('movement.component.create'))->with('error', 'Oops something went wrong! Please contact your administrator.');
                        }
                        break;
                }

                break;
            case 'outgoing':

                $reference = Component::findOrFail($input['reference']);
                $available_quantity = $reference->actualQuantity - $input['quantity'];
                if($reference->actualQuantity <= 0){
                    return redirect(route('movement.component.create'))->with('error', "Request Denied! $reference->partNumber | $reference->description has been depleted!");
                }
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
                    $item_movement_id = DB::table('movement_components')
                        ->insertGetId(
                            [
                                'type' => $input['type'],
                                'purpose' => ($input['purpose']) ? $input['purpose'] : null,
                                'rma' => ($input['rma']) ? $input['rma'] : null,
                                'reference_designator' => ($input['reference_designator']) ? $input['reference_designator'] : null,
                                'invoice_number' => $input['invoice_number'],
                                'reference' => $reference->id,
                                'quantity' => $deducted,
                                'date_received_released' => $input['date_received_released'],
                                'received_released_by' => $input['received_released_by'],
                                'remarks' => $input['remarks']
                            ]
                        );
                    $this->create_tracker(
                        [
                            'type' => $input['type'],
                            'item_movement' => $item_movement_id,
                            'component' => $reference->id,
                            'rma' => ($input['rma']) ? $input['rma'] : null,
                            'reference_designator' => ($input['reference_designator']) ? $input['reference_designator'] : null,
                            'previous_quantity' => $reference->actualQuantity,
                            'new_quantity' => $request_quantity,
                            'previous_unit_price' => $reference->unitPrice,
                            'new_unit_price' => $reference->unitPrice,
                            'date_of_transaction' => date('Y-m-d'),
                            'entry_by' => $_SESSION['user']
                        ]
                    );
                    $reference->update(['actualQuantity' => $request_quantity, 'totalPrice' => $available_quantity * $reference->unitPrice]);
                    //creating notification
                    if($reference->actualQuantity == 0) {
                        $this->notification->create($reference->id, $reference->description, $reference->partNumber, 'depleted', $_SESSION['user'], 'components', '/resources/component/'.$reference->id);
                    }
                    else if($reference->actualQuantity <= $reference->minimumQuantity) {
                        $this->notification->create($reference->id, $reference->description, $reference->partNumber, 'critical', $_SESSION['user'], 'components', '/resources/component/'.$reference->id);
                    }

                    DB::commit();
                    return redirect(route('movement.component.show', $item_movement_id))->with('response', $message);
                } catch (\Exception $exception) {
                    DB::rollBack();
                    $this->util->createError([
                        $_SESSION['user'],
                        'MOVCOMPADD',
                        $exception->getMessage()
                    ]);
                    return redirect(route('movement.component.create'))->with('error', 'Oops something went wrong! Please contact your administrator.');
                }

                break;
        }
    }
    public function revert($id)
    {
        $movement_reference = MovementComponent::find($id);
        $movement_tracker = MovementComponentTracker::where('item_movement', $id)->first();
        DB::beginTransaction();
        try {
            DB::table('components')
                ->where('id', $movement_reference->reference)
                ->update(['actualQuantity' => $movement_tracker->previous_quantity]);

            DB::table('movement_components')
                ->where('id', $id)
                ->update(['deleted_at' => date('Y-m-d')]);

            DB::table('movement_component_tracker')
                ->where('item_movement', $id)
                ->update(['status' => 'reverted']);
            DB::table('notification')->where('item', $movement_reference->reference)->delete();
            DB::commit();
            return redirect(route('movement.components'))->with('response', 'Movement successfully reverted!');
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->util->createError([
                $_SESSION['user'],
                'MOVCOMPREV',
                $exception->getMessage()
            ]);
            return redirect(route('movement.components'))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function update($id)
    {
        $data['data'] = DB::table('movement_components')
            ->leftJoin('components', 'components.id', '=', 'movement_components.reference')
            ->select(
                'movement_components.*',
                'components.partNumber',
                'components.description',
                'components.unitPrice'
            )
            ->where('movement_components.id', $id)
            ->first();
        $data['components'] = Component::query()->orderBy('partNumber', 'ASC')->get();
        $data['boards'] = Board::where('status', 1)->orderBy('rma', 'ASC')->get();
        $data['purposes'] = Purpose::query()->orderBy('purpose', 'ASC')->get();
        return view('movement.components.update', ['data' => $data, 'title' => 'Item Movement (Components)', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'components']);
    }
    public function upsave($id)
    {
        $movement_reference = MovementComponent::query()->findOrFail($id);
        $movement_tracker = MovementComponentTracker::query()->where('item_movement', $id)->first();
        $component = Component::query()->findorFail($movement_reference->reference);
        $input = $this->request->post();
        switch ($input['type']) {
            case 'incoming':
                if($movement_reference->reference != $input['reference']) {
                    DB::beginTransaction();
                    try {
                        //revert old reference
                        DB::table('components')
                            ->where('id', $movement_reference->reference)
                            ->update(
                                [
                                    'actualQuantity' => $movement_tracker->previous_quantity,
                                    'unitPrice' => $movement_tracker->previous_unitPrice,
                                    'totalPrice' => ($movement_tracker->previous_quantity * $movement_tracker->previous_unitPrice)
                                ]
                            );
                        //select the new reference
                        $new_component_query = DB::table('components')
                            ->where('id', $input['reference']);
                        $new_component = $new_component_query->first();
                        $new_component_query->update(['actualQuantity' => ($new_component->actualQuantity + $input['quantity'])]);
                        DB::table('movement_components')
                            ->where('id', $id)
                            ->update([
                                'type' => $input['type'],
                                'reference' => $input['reference'],
                                'quantity' => $input['quantity'],
                                'invoice_number' => $input['invoice_number'],
                                'vendor' => $input['vendor'],
                                'brand' => $input['brand'],
                            ]);
                        DB::table('movement_component_tracker')
                            ->where('item_movement', $id)
                            ->update([
                                'type' => $input['type'],
                                'component' => $new_component->id,
                                'previous_quantity' => $new_component->actualQuantity,
                                'new_quantity' => $new_component->actualQuantity,
                                'previous_unit_price' => $new_component->unitPrice,
                                'new_unit_price' => $new_component->unitPrice,
                                'date_of_transaction' => date('Y-m-d'),
                                'entryBy' => $_SESSION['user']
                            ]);

                        DB::commit();
                        return redirect(route('movement.component.show', $id))->with('response', "$new_component->partNumber | $new_component->description movement has been updated!");
                    } catch (\Exception $exception) {
                        DB::rollBack();
                        $this->util->createError([
                            $_SESSION['user'],
                            'MOVCOMPUPDATE',
                            $exception->getMessage()
                        ]);
                    }
                }
                else {
                    DB::beginTransaction();
                    try {
                        DB::table('components')
                            ->where('id', $movement_reference->reference)
                            ->update(
                                [
                                    'actualQuantity' =>  $movement_tracker->previous_quantity + $input['quantity'],
//                                    'unitPrice' =>  $input['unitPrice'],
//                                        'date_received_released' => $input['date_received_released'],
//                                        'received_released_by' => $input['received_released_by'],
                                    'remarks' => $input['remarks'],
                                ]
                            );
                        DB::table('movement_components')
                            ->where('id', $id)
                            ->update(
                                [
                                    'type' => $input['type'],
                                    'invoice_number' => $input['invoice_number'],
                                    'vendor' => ($input['vendor']) ? $input['vendor'] : null,
                                    'brand' => ($input['brand']) ? $input['brand'] : null,
                                    'quantity' => $input['quantity'],
                                    'date_received_released' => $input['date_received_released'],
                                    'received_released_by' => $input['received_released_by'],
                                    'purpose' => null,
                                ]
                            );
                        DB::table('movement_consumable_tracker')
                            ->where('item_movement', $id)
                            ->update(
                                [
                                    'type' => $input['type'],
                                    'new_quantity' => $movement_tracker->previous_quantity + $input['quantity'],
                                ]
                            );
                        DB::commit();
                        return redirect(route('movement.component.show', $id))->with('response', "$component->partNumber | $component->description movement successfully updated!");
                    } catch (\Exception $exception) {
                        DB::rollBack();
                        $this->util->createError([
                            $_SESSION['user'],
                            'MOVCOMPUPDATE',
                            $exception->getMessage()
                        ]);
                        return redirect(route('movement.component.show', $id))->with('error', 'Oops something went wrong! Please contact your administrator!');
                    }
                }
                break;
            case 'outgoing':
                if($movement_reference->reference != $input['reference']){
                    try {
                        //revert old ref
                        DB::table('components')
                            ->where('id', $$movement_reference->reference)
                            ->update(
                                [
                                    'actualQuantity' => $movement_tracker->previous_quantity,
                                    'unitPrice' => $movement_tracker->previous_unit_price,
                                    'totalPrice' => ($movement_tracker->previous_unit_price * $movement_tracker->previous_quantity)
                                ]
                            );
                        DB::commit();
//                        return redirect(route('movement.consumable.show', $id))->with('response', "$new_consumable->partNumber | $new_consumable->description movement has been updated!");
                    } catch (\Exception $exception) {
                        DB::rollBack();
                        $this->util->createError([
                            $_SESSION['user'],
                            'MOVCOMPUPDATE',
                            $exception->getMessage()
                        ]);
                        return redirect(route('movement.component.show', $id))->with('error', 'Oops something went wrong! Please contact your administrator!');
                    }
                    //new reference
                    $new_component_query = DB::table('components')
                        ->where('id', $input['reference']);
                    $new_component = $new_component_query->first();
                    $outgoing_availability = $new_component->actualQuantity - $input['quantity'];
                    switch (true) {
                        case $new_component->actualQuantity == 0:
                            return redirect(route('movement.component.show', $id))->with('error', "Transaction Denied! since $new_component->partNumber | $new_component->description has been depleted or has 0 balance!");
                            break;
                        case $outgoing_availability > 0:
                            try {

                                DB::table('movement_components')
                                    ->where('id', $id)
                                    ->update(
                                        [
                                            'type' => $input['type'],
                                            'purpose' => ($input['purpose']) ? $input['purpose'] : null,
                                            'rma' => ($input['rma']) ? $input['rma'] : null,
                                            'reference_designator' => ($input['reference_designator']) ? $input['reference_designator'] : null,
                                            'reference' => $input['reference'],
                                            'quantity' => $input['quantity'],
                                            'date_received_released' => $input['date_received_released'],
                                            'received_released_by' => $input['received_released_by'],
                                            'remarks' => ($input['remarks']) ? $input['remarks'] : null,
                                        ]
                                    );
                                DB::table('movement_component_tracker')
                                    ->where('item_movement', $id)
                                    ->update(
                                        [
                                            'type' => $input['type'],
                                            'component' => $input['reference'],
                                            'rma' => ($input['rma']) ? $input['rma'] : null,
                                            'reference_designator' => ($input['reference_designator']) ? $input['reference_designator'] : null,
                                            'previous_quantity' => $new_component->actualQuantity,
                                            'new_quantity' => $outgoing_availability,
                                            'status' => 'updated'
                                        ]
                                    );
                                $new_component_query->update(
                                    [
                                        'actualQuantity' =>  $outgoing_availability,
                                        'remarks' => $input['remarks'],
                                    ]
                                );
                                //creating notification
                                if($new_component->actualQuantity == 0) {
                                    $this->notification->create($new_component->id, $new_component->description, $new_component->partNumber, 'depleted', $_SESSION['user'], 'components', '/resources/consumable/'.$new_component->id);
                                }else if($new_component->actualQuantity <= $new_component->minimumQuantity) {
                                    $this->notification->create($new_component->id, $new_component->description, $new_component->partNumber, 'critical', $_SESSION['user'], 'components', '/resources/consumable/'.$new_component->id);
                                }

                                DB::commit();
                                return redirect(route('movement.component.show', $id))->with('response', "$component->partNumber | $component->description movement successfully updated!");
                            } catch (\Exception $exception) {
                                DB::rollBack();
                                $this->util->createError([
                                    $_SESSION['user'],
                                    'MOVCOMPUPDATE',
                                    $exception->getMessage()
                                ]);
                                return redirect(route('movement.component.show', $id))->with('error', 'Oops something went wrong! Please contact your administrator!');
                            }
                            break;
                        case $outgoing_availability <= 0:
                            try {
                                $difference = abs($outgoing_availability);
                                $current_quantity = 0;
                                DB::table('movement_components')
                                    ->where('id', $id)
                                    ->update(
                                        [
                                            'type' => $input['type'],
                                            'purpose' => ($input['purpose']) ? $input['purpose'] : null,
                                            'rma' => ($input['rma']) ? $input['rma'] : null,
                                            'reference_designator' => ($input['reference_designator']) ? $input['reference_designator'] : null,
                                            'quantity' => $new_component->actualQuantity,
                                            'date_received_released' => $input['date_received_released'],
                                            'received_released_by' => $input['received_released_by'],
                                            'remarks' => ($input['remarks']) ? $input['remarks'] : null,
                                        ]
                                    );
                                DB::table('movement_components_tracker')
                                    ->where('item_movement', $id)
                                    ->update(
                                        [
                                            'type' => $input['type'],
                                            'component' => $input['reference'],
                                            'rma' => ($input['rma']) ? $input['rma'] : null,
                                            'reference_designator' => ($input['reference_designator']) ? $input['reference_designator'] : null,
                                            'previous_quantity' => $new_component->actualQuantity,
                                            'new_quantity' => $outgoing_availability,
                                            'status' => 'updated'
                                        ]
                                    );
                                $new_component_query->update(
                                    [
                                        'actualQuantity' =>  $current_quantity,
                                        'totalQuantity' => $current_quantity * $new_component->unitPrice,
//                                        'date_received_released' => $input['date_received_released'],
//                                        'received_released_by' => $input['received_released_by'],
                                        'remarks' => $input['remarks'],
                                    ]
                                );
                                //creating notification
                                if($new_component->actualQuantity == 0) {
                                    $this->notification->create($new_component->id, $new_component->description, $new_component->partNumber, 'depleted', $_SESSION['user'], 'components', '/resources/component/'.$new_component->id);
                                }else if($new_component->actualQuantity <= $new_component->minimumQuantity) {
                                    $this->notification->create($new_component->id, $new_component->description, $new_component->partNumber, 'critical', $_SESSION['user'], 'components', '/resources/component/'.$new_component->id);
                                }
                                DB::commit();
                                return redirect(route('movement.consumable.show', $id))
                                    ->with('response', "$new_component->partNumber | $new_component->description movement successfully updated! but we cannot accommodate the remaining quantity of: $difference");
                            } catch (\Exception $exception) {
                                DB::rollBack();
                                $this->util->createError([
                                    $_SESSION['user'],
                                    'MOVCONSUPDATE',
                                    $exception->getMessage()
                                ]);
                                return redirect(route('movement.consumable.show', $id))->with('error', 'Oops something went wrong! Please contact your administrator!');
                            }
                            break;
                    }
                }
                else {
                    $outgoing_availability = ($movement_tracker->previous_quantity - $input['quantity']);
                    switch (true) {
                        case $movement_tracker->previous_quantity == 0:
                            return redirect(route('movement.component.show', $id))->with('error', "Transaction Denied! since $component->partNumber | $component->description has been depleted or has 0 balance!");
                            break;
                        case $outgoing_availability > 0:
                            try {
                                DB::table('consumables')
                                    ->where('id', $movement_reference->reference)
                                    ->update(
                                        [
                                            'actualQuantity' =>  $outgoing_availability,
                                            'totalPrice' => $outgoing_availability * $component->unitPrice,
//                                            'date_received_released' => $input['date_received_released'],
//                                            'received_released_by' => $input['received_released_by'],
                                            'remarks' => $input['remarks'],
                                        ]
                                    );
                                DB::table('movement_components')
                                    ->where('id', $id)
                                    ->update(
                                        [
                                            'type' => $input['type'],
                                            'purpose' => ($input['purpose']) ? $input['purpose'] : null,
                                            'rma' => ($input['rma']) ? $input['rma'] : null,
                                            'reference_designator' => ($input['reference_designator']) ? $input['reference_designator'] : null,
                                            'quantity' => $input['quantity'],
                                            'date_received_released' => $input['date_received_released'],
                                            'received_released_by' => $input['received_released_by'],
                                            'remarks' => ($input['remarks']) ? $input['remarks'] : null,
                                        ]
                                    );
                                DB::table('movement_component_tracker')
                                    ->where('item_movement', $id)
                                    ->update(
                                        [
                                            'type' => $input['type'],
                                            'new_quantity' => $outgoing_availability,
                                            'rma' => ($input['rma']) ? $input['rma'] : null,
                                            'reference_designator' => ($input['reference_designator']) ? $input['reference_designator'] : null,
                                            'status' => 'updated'
                                        ]
                                    );
                                $component->fresh();
                                if($component->actualQuantity == 0) {
                                    $this->notification->create($component->id, $component->description, $component->partNumber, 'depleted', $_SESSION['user'], 'components', '/resources/component/'.$component->id);
                                }else if($component->actualQuantity <= $component->minimumQuantity) {
                                    $this->notification->create($component->id, $component->description, $component->partNumber, 'critical', $_SESSION['user'], 'components', '/resources/component/'.$component->id);
                                }
                                DB::commit();
                                return redirect(route('movement.component.show', $id))->with('response', "$component->partNumber | $component->description movement successfully updated!");
                            } catch (\Exception $exception) {
                                DB::rollBack();
                                $this->util->createError([
                                    $_SESSION['user'],
                                    'MOVCOMPUPDATE',
                                    $exception->getMessage()
                                ]);
                                return redirect(route('movement.component.show', $id))->with('error', 'Oops something went wrong! Please contact your administrator!');
                            }
                            break;
                        case $outgoing_availability <= 0:
                            try {
                                $deducted = $movement_tracker->previous_quantity;
                                $difference = abs($outgoing_availability);
                                $current_quantity = 0;
                                DB::table('components')
                                    ->where('id', $movement_reference->reference)
                                    ->update(
                                        [
                                            'actualQuantity' =>  $current_quantity,
                                            'totalPrice' => $current_quantity * $component->actualQuantity,
//                                            'date_received_released' => $input['date_received_released'],
//                                            'received_released_by' => $input['received_released_by'],
                                            'remarks' => $input['remarks'],
                                        ]
                                    );
                                DB::table('movement_components')
                                    ->where('id', $id)
                                    ->update(
                                        [
                                            'type' => $input['type'],
                                            'purpose' => ($input['purpose']) ? $input['purpose'] : null,
                                            'rma' => ($input['rma']) ? $input['rma'] : null,
                                            'reference_designator' => ($input['reference_designator']) ? $input['reference_designator'] : null,
                                            'quantity' => $deducted,
                                            'date_received_released' => $input['date_received_released'],
                                            'received_released_by' => $input['received_released_by'],
                                            'remarks' => ($input['remarks']) ? $input['remarks'] : null,
                                        ]
                                    );
                                DB::table('movement_component_tracker')
                                    ->where('item_movement', $id)
                                    ->update(
                                        [
                                            'type' => $input['type'],
                                            'new_quantity' => $current_quantity,
                                            'rma' => ($input['rma']) ? $input['rma'] : null,
                                            'reference_designator' => ($input['reference_designator']) ? $input['reference_designator'] : null,
                                            'status' => 'updated'
                                        ]
                                    );
                                $component->fresh();
                                if($component->actualQuantity == 0) {
                                    $this->notification->create($component->id, $component->description, $component->partNumber, 'depleted', $_SESSION['user'], 'consumables', '/resources/component/'.$component->id);
                                }else if($component->actualQuantity <= $component->minimumQuantity) {
                                    $this->notification->create($component->id, $component->description, $component->partNumber, 'critical', $_SESSION['user'], 'consumables', '/resources/component/'.$component->id);
                                }
                                DB::commit();
                                return redirect(route('movement.component.show', $id))
                                    ->with('response', "$component->partNumber | $component->description movement successfully updated! but we cannot accommodate the remaining quantity of: $difference");
                            } catch (\Exception $exception) {
                                DB::rollBack();
                                $this->util->createError([
                                    $_SESSION['user'],
                                    'MOVCOMPUPDATE',
                                    $exception->getMessage()
                                ]);
                                return redirect(route('movement.component.show', $id))->with('error', 'Oops something went wrong! Please contact your administrator!');
                            }
                            break;
                    }
                }
                //outgoing
                break;
        }
    }
    public function create_tracker($data)
    {
        DB::table('movement_component_tracker')
            ->insert($data);
    }
}
