<?php

namespace App\Http\Controllers;

use App\Models\Consumable;
use App\Models\Location;
use App\Models\Movement;
use App\Models\MovementConsumable;
use App\Models\MovementConsumableTracker;
use App\Models\Unit;
use App\Services\ConsumableServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Purpose;

class MovementConsumableController extends Controller
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
        $data['incoming'] = DB::table('movement_consumables')
                                    ->leftJoin('consumables', 'consumables.id', '=', 'movement_consumables.reference')
                                    ->where([
                                        'type' => 'incoming',
                                        'deleted_at' => null,
                                    ])
                                    ->select(
                                        'movement_consumables.*',
                                        'consumables.description',
                                        'consumables.partNumber',
                                        'consumables.actualQuantity')
                                    ->paginate(10);

        $data['outgoing'] = DB::table('movement_consumables')
                                    ->leftJoin('consumables', 'consumables.id', '=', 'movement_consumables.reference')
                                    ->leftJoin('purposes', 'purposes.id', '=', 'movement_consumables.purpose')
                                    ->where([
                                        'type' => 'outgoing',
                                        'deleted_at' => null,
                                    ])
                                    ->select(
                                        'movement_consumables.*',
                                        'consumables.description',
                                        'consumables.partNumber',
                                        'consumables.actualQuantity',
                                        'purposes.purpose as purposeName',
                                        )
                                    ->paginate(10);

        return view('movement.consumables.index', ['data' => $data, 'title' => 'Item Movement (Consumables)', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'consumables']);
    }
    public function show($id)
    {
        $data = DB::table('movement_consumables')
                                    ->leftJoin('consumables', 'consumables.id', '=', 'movement_consumables.reference')
                                    ->leftJoin('purposes', 'purposes.id', '=', 'movement_consumables.purpose')
                                    ->where('movement_consumables.id', $id)
                                    ->select(
                                        'movement_consumables.*',
                                        'consumables.partNumber',
                                        'consumables.description',
                                        'consumables.unitPrice',
                                        'purposes.purpose as purpose',
                                    )
                                    ->first();
        return view('movement.consumables.show', ['data' => $data, 'title' => 'Item Movement (Consumables)', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'consumables']);
    }

    public function create()
    {
        $data['data'] = Consumable::query()
//                                        ->where(['is_out' => 0, 'deleted_at' => null])
                                        ->orderBy('description', 'ASC')
                                        ->get();

        $data['purpose'] = Purpose::orderBy('purpose', 'ASC')->get();
        $data['storedIn'] = Location::orderBy('location', 'ASC')->get();
        $data['unit'] = Unit::orderBy('unit', 'ASC')->get();

        return view('movement.consumables.create', ['data' => $data, 'title' => 'Item Movement (Consumables)', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'consumables']);
    }
    public function store(ConsumableServices $consumableServices)
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
                            $reference = Consumable::find($input['reference']);
                            $item_movement_id = DB::table('movement_consumables')->insertGetId(
                                [
                                    'type' => $input['type'],
                                    'invoice_number' => $input['invoice_number'],
                                    'reference' => $reference->id,
                                    'vendor' => $input['vendor'],
                                    'brand' => $input['brand'],
                                    'quantity' => $input['quantity'],
                                    'date_received_released' => $input['date_received_released'],
                                    'received_released_by' => $input['received_released_by'],
                                    'remarks' => $input['remarks']
                                ]
                            );
                            $this->create_tracker([
                                'type' => $input['type'],
                                'item_movement' => $item_movement_id,
                                'consumable' => $input['reference'],
                                'previous_quantity' => $reference->actualQuantity,
                                'new_quantity' => ($input['quantity'] + $reference->actualQuantity),
//                                'previous_unit_price' => $reference->unitPrice,
//                                'new_unit_price' => ($reference->unitPrice * $input['quantity']),
                                'date_of_transaction' => date('Y-m-d'),
                                'entry_by' => $_SESSION['user']
                            ]);
                            $reference->update(['actualQuantity' => ($input['quantity'] + $reference->actualQuantity)]);
                            DB::commit();
                            return redirect(route('movement.consumable.show', $item_movement_id))->with('response', "Incoming for $reference->partNumber | $reference->description has been recorded!");
                        } catch (\Exception $exception) {
                            DB::rollBack();
                            $this->util->createError([
                                $_SESSION['user'],
                                'MOVCONSADD',
                                $exception->getMessage()
                            ]);
                            return redirect(route('movement.consumable.create'))->with('error', 'Oops something went wrong! Please contact your administrator.');
                        }
                        break;
                    case 'new':
                        DB::beginTransaction();
                        if($consumableServices->ifExist($input)) {
                            return redirect(route('movement.consumable.create'))->with('error', 'The new Consumable record that you are entering has already been recorded previously!');
                        }
                        try {
                            $reference_id = DB::table('consumables')
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
                            $reference = DB::table('consumables')->find($reference_id);
                            $item_movement_id = DB::table('movement_consumables')->insertGetId(
                                [
                                    'type' => $input['type'],
                                    'vendor' => $input['vendor'],
                                    'brand' => $input['brand'],
                                    'invoice_number' => $input['invoice_number'],
                                    'reference' => $reference_id,
                                    'quantity' => $input['quantity'],
                                    'date_received_released' => $input['date_received_released'],
                                    'received_released_by' => $input['received_released_by'],
                                    'remarks' => $input['remarks']
                                ]
                            );
                            $this->create_tracker([
                                'type' => $input['type'],
                                'item_movement' => $item_movement_id,
                                'consumable' => $reference_id,
                                'previous_quantity' => 0,
                                'new_quantity' => $input['quantity'],
                                'previous_unit_price' => 0,
                                'new_unit_price' => $reference->unitPrice ,
                                'date_of_transaction' => date('Y-m-d'),
                                'entry_by' => $_SESSION['user']
                            ]);
                            DB::commit();
                            return redirect(route('movement.consumable.show', $item_movement_id))->with('response', "Incoming for $reference->partNumber | $reference->description has been recorded!");
                        }
                        catch (\Exception $exception) {
                            DB::rollBack();
                            $this->util->createError([
                                $_SESSION['user'],
                                'MOVCONSADD',
                                $exception->getMessage()
                            ]);
                            return redirect(route('movement.consumable.create'))->with('error', 'Oops something went wrong! Please contact your administrator.');
                        }
                        break;
                }

                break;
            case 'outgoing':

                $reference = Consumable::find($input['reference']);
                $available_quantity = $reference->actualQuantity - $input['quantity'];
                if($reference->actualQuantity <= 0){
                    return redirect(route('movement.consumable.create'))->with('error', "Request Denied! $reference->partNumber | $reference->description has been depleted!");
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
                    $item_movement_id = DB::table('movement_consumables')
                        ->insertGetId(
                            [
                                'type' => $input['type'],
                                'purpose' => $input['purpose'],
                                'brand' => ($input['purpose']) ? $input['purpose'] : null,
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
                            'consumable' => $reference->id,
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
                        $this->notification->create($reference->id, $reference->description, $reference->partNumber, 'depleted', $_SESSION['user'], 'consumables', '/resources/consumable/'.$reference->id);
                    }
                    else if($reference->actualQuantity <= $reference->minimumQuantity) {
                        $this->notification->create($reference->id, $reference->description, $reference->partNumber, 'critical', $_SESSION['user'], 'consumables', '/resources/consumable/'.$reference->id);
                    }

                    DB::commit();
                    return redirect(route('movement.consumable.show', $item_movement_id))->with('response', $message);
                } catch (\Exception $exception) {
                    DB::rollBack();
                    $this->util->createError([
                        $_SESSION['user'],
                        'MOVCONSADD',
                        $exception->getMessage()
                    ]);
                    return redirect(route('movement.consumable.create'))->with('error', 'Oops something went wrong! Please contact your administrator.');
                }

                break;
        }
    }
    public function revert($id)
    {
        $movement_reference = MovementConsumable::find($id);
        $movement_tracker = MovementConsumableTracker::where('item_movement', $id)->first();
        DB::beginTransaction();
        try {
            DB::table('consumables')
                ->where('id', $movement_reference->reference)
                ->update(['actualQuantity' => $movement_tracker->previous_quantity]);

            DB::table('movement_consumables')
                ->where('id', $id)
                ->update(['deleted_at' => date('Y-m-d')]);

            DB::table('movement_consumable_tracker')
                ->where('item_movement', $id)
                ->update(['status' => 'reverted']);
            DB::table('notification')->where('item', $movement_reference->reference)->delete();
            DB::commit();
            return redirect(route('movement.consumables'))->with('response', 'Movement successfully reverted!');
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->util->createError([
                $_SESSION['user'],
                'MOVCONSREV',
                $exception->getMessage()
            ]);
            return redirect(route('movement.consumables'))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function update($id)
    {
        $data['data'] = DB::table('movement_consumables')
                                ->leftJoin('consumables', 'consumables.id', '=', 'movement_consumables.reference')
                                ->select(
                                    'movement_consumables.*',
                                    'consumables.partNumber',
                                    'consumables.description',
                                    'consumables.unitPrice'
                                )
                                ->where('movement_consumables.id', $id)
                                ->first();
        $data['consumables'] = Consumable::query()->orderBy('partNumber', 'ASC')->get();
        $data['purposes'] = Purpose::query()->orderBy('purpose', 'ASC')->get();
        return view('movement.consumables.update', ['data' => $data, 'title' => 'Item Movement (Consumables)', 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => 'consumables']);
    }
    public function upsave($id)
    {
        $movement_reference = MovementConsumable::query()->findOrFail($id);
        $movement_tracker = MovementConsumableTracker::query()->where('item_movement', $id)->first();
        $consumable = Consumable::query()->findorFail($movement_reference->reference);
        $input = $this->request->post();
        switch ($input['type']) {
            case 'incoming':
                if($movement_reference->reference != $input['reference']) {
                    DB::beginTransaction();
                    try {
                        //revert old reference
                        DB::table('consumables')
                                ->where('id', $movement_reference->reference)
                                ->update(
                                    [
                                        'actualQuantity' => $movement_tracker->previous_quantity,
                                        'unitPrice' => $movement_tracker->previous_unitPrice,
                                        'totalPrice' => ($movement_tracker->previous_quantity * $movement_tracker->previous_unitPrice)
                                    ]
                                );
                        //select the new reference
                        $new_consumable_query = DB::table('consumables')
                                                ->where('id', $input['reference']);
                        $new_consumable = $new_consumable_query->first();
                        $new_consumable_query->update(['actualQuantity' => ($new_consumable->actualQuantity + $input['quantity'])]);
                        DB::table('movement_consumables')
                                ->where('id', $id)
                                ->update([
                                    'type' => $input['type'],
                                    'reference' => $input['reference'],
                                    'quantity' => $input['quantity'],
                                    'invoice_number' => $input['invoice_number'],
                                    'vendor' => $input['vendor'],
                                    'brand' => $input['brand'],
                                    'purpose' => null
                                ]);
                        DB::table('movement_consumable_tracker')
                                ->where('item_movement', $id)
                                ->update([
                                    'type' => $input['type'],
                                    'consumable' => $new_consumable->id,
                                    'previous_quantity' => $new_consumable->actualQuantity,
                                    'new_quantity' => $new_consumable->actualQuantity,
                                    'previous_unit_price' => $new_consumable->unitPrice,
                                    'new_unit_price' => $new_consumable->unitPrice,
                                    'date_of_transaction' => date('Y-m-d'),
                                    'entryBy' => $_SESSION['user']
                                ]);

                        DB::commit();
                        return redirect(route('movement.consumable.show', $id))->with('response', "$new_consumable->partNumber | $new_consumable->description movement has been updated!");
                    } catch (\Exception $exception) {
                        DB::rollBack();
                        $this->util->createError([
                            $_SESSION['user'],
                            'MOVCONSUPDATE',
                            $exception->getMessage()
                        ]);
                    }
                }
                else {
                    DB::beginTransaction();
                    try {
                        DB::table('consumables')
                                ->where('id', $movement_reference->reference)
                                ->update(
                                    [
                                        'actualQuantity' =>  $movement_tracker->previous_quantity + $input['quantity'],
                                        'unitPrice' =>  $input['unitPrice'],
//                                        'date_received_released' => $input['date_received_released'],
//                                        'received_released_by' => $input['received_released_by'],
                                        'remarks' => $input['remarks'],
                                    ]
                                );
                        DB::table('movement_consumables')
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
                                        'previous_unit_price' => $consumable->unitPrice,
                                        'new_unit_price' => $input['unitPrice'],
                                    ]
                                );
                        DB::commit();
                        return redirect(route('movement.consumable.show', $id))->with('response', "$consumable->partNumber | $consumable->description movement successfully updated!");
                    } catch (\Exception $exception) {
                        DB::rollBack();
                        $this->util->createError([
                            $_SESSION['user'],
                            'MOVCONSUPDATE',
                            $exception->getMessage()
                        ]);
                        return redirect(route('movement.consumable.show', $id))->with('error', 'Oops something went wrong! Please contact your administrator!');
                    }
                }
                break;
            case 'outgoing':
                if($movement_reference->reference != $input['reference']){
                    try {
                        //revert old ref
                        DB::table('consumables')
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
                            'MOVCONSUPDATE',
                            $exception->getMessage()
                        ]);
                        return redirect(route('movement.consumable.show', $id))->with('error', 'Oops something went wrong! Please contact your administrator!');
                    }
                        //new reference
                        $new_consumable_query = DB::table('consumables')
                                                        ->where('id', $input['reference']);
                        $new_consumable = $new_consumable_query->first();
                        $outgoing_availability = $new_consumable->actualQuantity - $input['quantity'];
                        switch (true) {
                            case $new_consumable->actualQuantity == 0:
                                return redirect(route('movement.consumable.show', $id))->with('error', "Transaction Denied! since $new_consumable->partNumber | $new_consumable->description has been depleted or has 0 balance!");
                                break;
                            case $outgoing_availability > 0:
                                try {

                                    DB::table('movement_consumables')
                                        ->where('id', $id)
                                        ->update(
                                            [
                                                'type' => $input['type'],
                                                'reference' => $input['reference'],
                                                'brand' => ($input['brand']) ? $input['brand'] : null,
                                                'quantity' => $input['quantity'],
                                                'date_received_released' => $input['date_received_released'],
                                                'received_released_by' => $input['received_released_by'],
                                                'purpose' => ($input['purpose']) ? $input['purpose'] : null,
                                            ]
                                        );
                                    DB::table('movement_consumable_tracker')
                                        ->where('item_movement', $id)
                                        ->update(
                                            [
                                                'type' => $input['type'],
                                                'consumable' => $input['reference'],
                                                'previous_quantity' => $new_consumable->actualQuantity,
                                                'new_quantity' => $outgoing_availability,
                                                'status' => 'updated'
                                            ]
                                        );
                                    $new_consumable_query->update(
                                        [
                                            'actualQuantity' =>  $outgoing_availability,
                                            'remarks' => $input['remarks'],
                                        ]
                                    );
                                    //creating notification
                                    if($new_consumable->actualQuantity == 0) {
                                        $this->notification->create($new_consumable->id, $new_consumable->description, $new_consumable->partNumber, 'depleted', $_SESSION['user'], 'consumables', '/resources/consumable/'.$new_consumable->id);
                                    }else if($new_consumable->actualQuantity <= $new_consumable->minimumQuantity) {
                                        $this->notification->create($new_consumable->id, $new_consumable->description, $new_consumable->partNumber, 'critical', $_SESSION['user'], 'consumables', '/resources/consumable/'.$new_consumable->id);
                                    }

                                    DB::commit();
                                    return redirect(route('movement.consumable.show', $id))->with('response', "$new_consumable->partNumber | $new_consumable->description movement successfully updated!");
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
                            case $outgoing_availability <= 0:
                                try {
                                    $difference = abs($outgoing_availability);
                                    $current_quantity = 0;
                                    DB::table('movement_consumables')
                                        ->where('id', $id)
                                        ->update(
                                            [
                                                'type' => $input['type'],
                                                'brand' => ($input['brand']) ? $input['brand'] : null,
                                                'quantity' => $new_consumable->actualQuantity,
                                                'date_received_released' => $input['date_received_released'],
                                                'received_released_by' => $input['received_released_by'],
                                                'purpose' => ($input['purpose']) ? $input['purpose'] : null,
                                            ]
                                        );
                                    DB::table('movement_consumable_tracker')
                                        ->where('item_movement', $id)
                                        ->update(
                                            [
                                                'type' => $input['type'],
                                                'previous_quantity' => $new_consumable->actualQuantity,
                                                'new_quantity' => $current_quantity,
                                                'status' => 'updated'
                                            ]
                                        );
                                    $new_consumable_query->update(
                                        [
                                            'actualQuantity' =>  $current_quantity,
                                            'date_received_released' => $input['date_received_released'],
                                            'received_released_by' => $input['received_released_by'],
                                            'remarks' => $input['remarks'],
                                        ]
                                    );
                                    //creating notification
                                    if($new_consumable->actualQuantity == 0) {
                                        $this->notification->create($new_consumable->id, $new_consumable->description, $new_consumable->partNumber, 'depleted', $_SESSION['user'], 'consumables', '/resources/consumable/'.$new_consumable->id);
                                    }else if($new_consumable->actualQuantity <= $new_consumable->minimumQuantity) {
                                        $this->notification->create($new_consumable->id, $new_consumable->description, $new_consumable->partNumber, 'critical', $_SESSION['user'], 'consumables', '/resources/consumable/'.$new_consumable->id);
                                    }
                                    DB::commit();
                                    return redirect(route('movement.consumable.show', $id))
                                        ->with('response', "$new_consumable->partNumber | $new_consumable->description movement successfully updated! but we cannot accommodate the remaining quantity of: $difference");
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
                            return redirect(route('movement.consumable.show', $id))->with('error', "Transaction Denied! since $consumable->partNumber | $consumable->description has been depleted or has 0 balance!");
                            break;
                        case $outgoing_availability > 0:
                            try {
                                DB::table('consumables')
                                    ->where('id', $movement_reference->reference)
                                    ->update(
                                        [
                                            'actualQuantity' =>  $outgoing_availability,
//                                            'date_received_released' => $input['date_received_released'],
//                                            'received_released_by' => $input['received_released_by'],
                                            'remarks' => $input['remarks'],
                                        ]
                                    );
                                DB::table('movement_consumables')
                                    ->where('id', $id)
                                    ->update(
                                        [
                                            'type' => $input['type'],
                                            'brand' => ($input['brand']) ? $input['brand'] : null,
                                            'quantity' => $input['quantity'],
                                            'date_received_released' => $input['date_received_released'],
                                            'received_released_by' => $input['received_released_by'],
                                            'purpose' => ($input['purpose']) ? $input['purpose'] : null,
                                        ]
                                    );
                                DB::table('movement_consumable_tracker')
                                    ->where('item_movement', $id)
                                    ->update(
                                        [
                                            'type' => $input['type'],
                                            'new_quantity' => $outgoing_availability,
                                        ]
                                    );
                                $consumable->fresh();
                                if($consumable->actualQuantity == 0) {
                                    $this->notification->create($consumable->id, $consumable->description, $consumable->partNumber, 'depleted', $_SESSION['user'], 'consumables', '/resources/consumable/'.$consumable->id);
                                }else if($consumable->actualQuantity <= $consumable->minimumQuantity) {
                                    $this->notification->create($consumable->id, $consumable->description, $consumable->partNumber, 'critical', $_SESSION['user'], 'consumables', '/resources/consumable/'.$consumable->id);
                                }
                                DB::commit();
                                return redirect(route('movement.consumable.show', $id))->with('response', "$consumable->partNumber | $consumable->description movement successfully updated!");
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
                        case $outgoing_availability <= 0:
                            try {
                                $deducted = $movement_tracker->previous_quantity;
                                $difference = abs($outgoing_availability);
                                $current_quantity = 0;
                                DB::table('consumables')
                                    ->where('id', $movement_reference->reference)
                                    ->update(
                                        [
                                            'actualQuantity' =>  $current_quantity,
//                                            'date_received_released' => $input['date_received_released'],
//                                            'received_released_by' => $input['received_released_by'],
                                            'remarks' => $input['remarks'],
                                        ]
                                    );
                                DB::table('movement_consumables')
                                    ->where('id', $id)
                                    ->update(
                                        [
                                            'type' => $input['type'],
                                            'brand' => ($input['brand']) ? $input['brand'] : null,
                                            'quantity' => $deducted,
                                            'date_received_released' => $input['date_received_released'],
                                            'received_released_by' => $input['received_released_by'],
                                            'purpose' => ($input['purpose']) ? $input['purpose'] : null,
                                        ]
                                    );
                                DB::table('movement_consumable_tracker')
                                    ->where('item_movement', $id)
                                    ->update(
                                        [
                                            'type' => $input['type'],
                                            'new_quantity' => $current_quantity,
                                        ]
                                    );
                                $consumable->fresh();
                                if($consumable->actualQuantity == 0) {
                                    $this->notification->create($consumable->id, $consumable->description, $consumable->partNumber, 'depleted', $_SESSION['user'], 'consumables', '/resources/consumable/'.$consumable->id);
                                }else if($consumable->actualQuantity <= $consumable->minimumQuantity) {
                                    $this->notification->create($consumable->id, $consumable->description, $consumable->partNumber, 'critical', $_SESSION['user'], 'consumables', '/resources/consumable/'.$consumable->id);
                                }
                                DB::commit();
                                return redirect(route('movement.consumable.show', $id))
                                                    ->with('response', "$consumable->partNumber | $consumable->description movement successfully updated! but we cannot accommodate the remaining quantity of: $difference");
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
                //outgoing
                break;
        }
    }
    public function create_tracker($data)
    {
        DB::table('movement_consumable_tracker')
                ->insert($data);
    }
}
