<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Boardtype;
use App\Models\Component;
use App\Models\Consigned;
use App\Models\Consumable;
use App\Models\Equipment;
use App\Models\Movement;
use App\Models\Purpose;
use App\Models\SystemType;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MovementController extends Controller
{
    private $util, $notification;
    public function __construct()
    {
        $this->util = new UtilitiesController();
        $this->notification = new NotificationController();
    }

    public function index($originGroup)
    {
        switch ($originGroup)
        {
            case 'consumables':
                $table = 'consumables';
                $title = 'Consumable(s)';
                $nav_selected = 'consumables';
                break;
            case 'components':
                $table = 'components';
                $title = 'Component(s)';
                $nav_selected = 'components';
                break;
            case 'consigned':
                $table = 'consignedspares';
                $title = 'Consigned Spare(s)';
                $nav_selected = 'consigned';
                break;
            case 'equipment':
                $table = 'equipment';
                $title = 'Equipment(s)';
                $nav_selected = 'equipment';
                break;
            case 'tools':
                $table = 'tools';
                $title = 'Tool(s)';
                $nav_selected = 'tools';
                break;
        }
        $data['incoming'] = DB::table('movements')
            ->join($table, $table.'.id', '=', 'movements.reference')
            ->where([
                'origin' => $nav_selected,
                'type' => 'incoming',
            ])
            ->select('movements.*', $table.'.description as originName', $table.'.actualQuantity as actualQuantity')
            ->paginate(10);

        $data['outgoing'] = DB::table('movements')
            ->join($table, $table.'.id', '=', 'movements.reference')
            ->join('purposes', 'purposes.id', '=', 'movements.purpose')
            ->where([
                'origin' => $nav_selected,
                'type' => 'outgoing',
            ])
            ->select('movements.*', $table.'.description as originName', $table.'.actualQuantity as actualQuantity', 'purposes.purpose as purposeName')
            ->paginate(10);
        return view('movement.index', ['data' => $data, 'title' => $title, 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => $nav_selected]);
    }
    public function show($originGroup, $id)
    {
        switch ($originGroup)
        {
            case 'consumables':
                $table = 'consumables';
                $view = 'movement.consumables.show';
                $title = 'Consumable(s)';
                $nav_selected = 'consumables';
                $query['incoming'] = DB::table('movements')
                    ->leftJoin($table, $table.'.id', '=', 'movements.reference')
                    ->leftJoin('purposes', 'purposes.id', '=', 'movements.purpose')
                    ->leftJoin('boards', 'boards.id', '=', 'movements.rma')
                    ->where('movements.id', $id)
                    ->select('movements.*', $table.'.description as originName', 'purposes.purpose as purpose', 'boards.rma as rma', 'boards.id as rmaID')
                    ->first();
                $query['purpose'] = DB::table('movements')
                    ->join($table, $table.'.id', '=', 'movements.reference')
                    ->join('purposes', 'purposes.id', '=', 'movements.purpose')
                    ->where('movements.id', $id)
                    ->select( 'purposes.purpose as purpose')
                    ->first();
                break;
            case 'components':
                $table = 'components';
                $view = 'movement.components.show';
                $title = 'Component(s)';
                $nav_selected = 'components';
                $query['incoming'] = DB::table('movements')
                    ->leftJoin($table, $table.'.id', '=', 'movements.reference')
                    ->leftJoin('purposes', 'purposes.id', '=', 'movements.purpose')
                    ->leftJoin('boards', 'boards.id', '=', 'movements.rma')
                    ->where('movements.id', $id)
                    ->select('movements.*', $table.'.description as originName', 'purposes.purpose as purpose', 'boards.rma as rma', 'boards.id as rmaID')
                    ->first();
                $query['purpose'] = DB::table('movements')
                    ->join($table, $table.'.id', '=', 'movements.reference')
                    ->join('purposes', 'purposes.id', '=', 'movements.purpose')
                    ->where('movements.id', $id)
                    ->select( 'purposes.purpose as purpose')
                    ->first();
                break;
            case 'consigned':
                $table = 'consignedspares';
                $view = 'movement.consigned.show';
                $title = 'Consigned Spare(s)';
                $nav_selected = 'consigned';
                $query['incoming'] = DB::table('movements')
                    ->leftJoin('systemTypes', 'systemTypes.id', '=', 'movements.systemType')
                    ->leftJoin('boardTypes', 'boardTypes.id', '=', 'movements.boardType')
                    ->leftJoin('consignedspares', 'consignedspares.id', '=', 'movements.reference')
                    ->leftJoin('purposes', 'purposes.id', '=', 'movements.purpose')
                    ->leftJoin('boards', 'boards.id', '=', 'movements.rma')
                    ->where('movements.id', $id)
                    ->select('movements.*', 'consignedspares.description as originName', 'SystemTypes.systemType as systemType',
                        'boardTypes.boardType as boardType', 'purposes.purpose as purpose', 'boards.rma as rma', 'boards.id as rmaID')
                    ->first();
                $query['purpose'] = DB::table('movements')
                    ->join($table, $table.'.id', '=', 'movements.reference')
                    ->join('purposes', 'purposes.id', '=', 'movements.purpose')
                    ->where('movements.id', $id)
                    ->select( 'purposes.purpose as purpose')
                    ->first();
                break;
            case 'equipment':
                $table = 'equipment';
                $view = 'movement.equipment.show';
                $title = 'Equipment(s)';
                $nav_selected = 'equipment';
                $query['incoming'] = DB::table('movements')
                    ->join($table, $table.'.id', '=', 'movements.reference')
                    ->where('movements.id', $id)
                    ->select('movements.*', $table.'.description as originName')
                    ->first();
                $query['purpose'] = DB::table('movements')
                    ->join($table, $table.'.id', '=', 'movements.reference')
                    ->join('purposes', 'purposes.id', '=', 'movements.purpose')
                    ->where('movements.id', $id)
                    ->select( 'purposes.purpose as purpose')
                    ->first();
                break;
            case 'tools':
                $table = 'tools';
                $view = 'movement.tools.show';
                $title = 'Tool(s)';
                $nav_selected = 'tools';
                $query['incoming'] = DB::table('movements')
                    ->join($table, $table.'.id', '=', 'movements.reference')
                    ->where('movements.id', $id)
                    ->select('movements.*', $table.'.description as originName')
                    ->first();
                $query['purpose'] = DB::table('movements')
                    ->join($table, $table.'.id', '=', 'movements.reference')
                    ->join('purposes', 'purposes.id', '=', 'movements.purpose')
                    ->where('movements.id', $id)
                    ->select( 'purposes.purpose as purpose')
                    ->first();
                break;
        }

        $query['purpose'] = DB::table('movements')
                            ->join($table, $table.'.id', '=', 'movements.reference')
                            ->join('purposes', 'purposes.id', '=', 'movements.purpose')
                            ->where('movements.id', $id)
                            ->select( 'purposes.purpose as purpose')
                            ->first();

        return view($view, ['data' => $query, 'title' => $title, 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => $nav_selected]);
    }
    public function update($originGroup, $id)
    {
        switch ($originGroup)
        {
            case 'consumables':
                $table = 'consumables';
                $view = 'movement.consumables.update';
                $title = 'Consumable(s)';
                $nav_selected = 'consumables';
                $query['consumables'] = Consumable::orderBy('description', 'ASC')->get();
                break;
            case 'components':
                $table = 'components';
                $view = 'movement.components.update';
                $title = 'Component(s)';
                $nav_selected = 'components';
                $query['components'] = Component::orderBy('description', 'ASC')->get();
                break;
            case 'consigned':
                $table = 'consignedspares';
                $view = 'movement.consigned.update';
                $title = 'Consigned Spare(s)';
                $nav_selected = 'consigned';
                $query['consigned'] = Consigned::orderBy('description', 'ASC')->get();
                break;
            case 'equipment':
                $table = 'equipment';
                $view = 'movement.equipment.update';
                $title = 'Equipment(s)';
                $nav_selected = 'equipment';
                $query['equipment'] = Equipment::orderBy('description', 'ASC')->get();
                break;
            case 'tools':
                $table = 'tools';
                $view = 'movement.tools.update';
                $title = 'Tool(s)';
                $nav_selected = 'tools';
                $query['tools'] = Tool::orderBy('description', 'ASC')->get();
                break;
        }
        $query['data'] = DB::table('movements')
            ->join($table, $table.'.id', '=', 'movements.reference')
            ->where('movements.id', $id)
            ->select('movements.*', $table.'.description as originName')
            ->first();

        $query['purposes'] = DB::table('purposes')->orderBy('purpose', 'ASC')->get();
        $query['boards'] = Board::orderBy('rma', 'ASC')->get();
        $query['systemTypes'] = SystemType::orderBy('systemType', 'ASC')->get();
        $query['boardTypes'] = Boardtype::orderBy('boardType', 'ASC')->get();

        return view($view, ['data' => $query, 'title' => $title, 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => $nav_selected]);
    }
    public function create($originGroup, Request $request)
    {
        switch ($originGroup)
        {
            case 'consumables':
                $query['data'] = Consumable::orderBy('description', 'ASC')->get();
                $view = 'movement.consumables.create';
                $title = 'Consumable(s)';
                $storeRoute = 'movement.consumables.store';
                $nav_selected = 'consumables';
                break;
            case 'components':
                $query['data'] = Component::orderBy('description', 'ASC')->get();
                $view = 'movement.components.create';
                $title = 'Component(s)';
                $storeRoute = 'movement.components.store';
                $nav_selected = 'components';
                break;
            case 'consigned':
                $query['data'] = Consigned::orderBy('description', 'ASC')->get();
                $view = 'movement.consigned.create';
                $title = 'Consigned Spare(s)';
                $storeRoute = 'movement.consigned.store';
                $nav_selected = 'consigned';
                break;
            case 'equipment':
                $query['data'] = Equipment::orderBy('description', 'ASC')->get();
                $view = 'movement.equipment.create';
                $title = 'Equipment(s)';
                $storeRoute = 'movement.equipment.store';
                $nav_selected = 'equipment';
                break;
            case 'tools':
                $query['data'] = Tool::orderBy('description', 'ASC')->get();
                $view = 'movement.tools.create';
                $title = 'Tool(s)';
                $storeRoute = 'movement.tools.store';
                $nav_selected = 'tools';
                break;
        }
        $query['purpose'] = Purpose::orderBy('purpose', 'ASC')->get();
        $query['boards'] = Board::orderBy('rma', 'ASC')->get();
        $query['systemTypes'] = SystemType::orderBy('description', 'ASC')->get();
        $query['boardTypes'] = Boardtype::orderBy('description', 'ASC')->get();
        return view($view, ['data' => $query, 'title' => $title, 'route' => $storeRoute, 'sidebar_selected' => 'Item Movements', 'reference_nav_selected' => $nav_selected]);
    }
    public function consumablesCreate(Request $request)
    {
        $reference = Consumable::find($request->input('reference'));
        if(empty($request))
        {
            return redirect('movements.create', 'consumables')->with('error', 'Consumable Record not Found!');
        }
        DB::beginTransaction();
        try
        {
            switch ($request->input('type'))
            {
                case 'incoming':
                    //total quantity if incoming will push through
                    $updateQuantity = intval($reference->actualQuantity) + intval($request->input('quantity'));
                    //total price from the existing record plus incoming
                    $incomingTotalPrice = intval($request->input('quantity') * $request->input('unitPrice'));
                    $finalPrice = (intval($reference->totalPrice) + $incomingTotalPrice);
                    $input = $request->except(['_token']);
                    $input['totalPrice'] = $incomingTotalPrice;
                    $input['dateAdded'] = date('Y-m-d');
                    $input['month'] = date('F', strtotime($input['dateReceived']));
                    $input['year'] = date('Y', strtotime($input['dateReceived']));;
                    $input['origin'] = 'consumables';
                    $input['entryBy'] = $_SESSION['user'];
                    $id = DB::table('movements')->insertGetId($input);
                    $this->createMovementTracker(
                        $id, $reference->id, $reference->actualQuantity, $updateQuantity, $request->input('type'),
                        $_SESSION['user'], 'consumables',$reference->totalPrice, $finalPrice
                    );
                    $reference->update([
                        'actualQuantity' => $updateQuantity,
                        'totalPrice' => $finalPrice
                    ]);
                    $response = 'You have recorded an Item Movement!';
                    break;
                case 'outgoing':
                    //total quantity if incoming will push through
                    if($reference->actualQuantity <= 0)
                    {
                        $this->notification->create($reference->id, $reference->partNumber, 'depleted', $_SESSION['user'], 'consumables', '/resources/consumable/'.$reference->id);
                        return redirect(route('movements.create', 'consumables'))->with('error', 'Consumable item has been depleted and we cannot proceed with the item movement!');
                    }
                    else
                    {
                        $updateQuantity = intval($reference->actualQuantity) - intval($request->input('quantity'));

                        //total price from the existing record plus incoming
                        $outgoingTotalPrice = intval($request->input('quantity') * $request->input('unitPrice'));
                        $finalPrice = (intval($reference->totalPrice) - $outgoingTotalPrice);
                        if($finalPrice < 0) $finalPrice = 0;
                        $input = $request->except(['_token']);
                        if($updateQuantity <= 0 )
                        {
                            $difference = abs($updateQuantity);
                            $updateQuantity = 0;
                            $input['quantity'] = abs($request->input('quantity') - @$difference);
                            if($difference != 0)
                            {
                                $response = 'Your item movement request went through but we can no longer accommodate '.@$difference.' more since Consumable item has been depleted!';
                            }
                            else
                            {
                                $response = 'Item Move has been recorded!';
                            }
                        }
                        else
                        {
                            $response = 'Item Move has been recorded!';
                        }
                        $input['totalPrice'] = $outgoingTotalPrice;
                        $input['dateAdded'] = date('Y-m-d');
                        $input['month'] = date('F', strtotime($input['dateReceived']));
                        $input['year'] = date('Y', strtotime($input['dateReceived']));;
                        $input['origin'] = 'consumables';
                        $input['entryBy'] = $_SESSION['user'];

                        $id = DB::table('movements')->insertGetId($input);
                        $this->createMovementTracker(
                            $id, $reference->id, $reference->actualQuantity, $updateQuantity, $request->input('type'),
                            $_SESSION['user'], 'consumables',$reference->totalPrice, $finalPrice
                        );
                        $reference->update([
                            'actualQuantity' => $updateQuantity,
                            'totalPrice' => $finalPrice
                        ]);
                    }
                    break;
            }
            $fresh_reference = $reference->fresh();
            if($fresh_reference->actualQuantity <= $fresh_reference->minimumQuantity)
            {
                $fresh_reference->update(['criticalTagging' => 'critical']);
                $this->notification->create($fresh_reference->id, $fresh_reference->partNumber, 'critical', $_SESSION['user'], 'consumables', '/resources/consumable/'.$fresh_reference->id);
            }
            if($fresh_reference->actualQuantity == 0)
            {
                $fresh_reference->update(['criticalTagging' => 'depleted']);
                $this->notification->create($fresh_reference->id, $fresh_reference->partNumber, 'depleted', $_SESSION['user'], 'consumables', '/resources/consumable/'.$fresh_reference->id);
            }
            DB::commit();
            return redirect(route('movements.create', 'consumables'))->with('response', $response);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            $this->util->createError([
                $_SESSION['user'],
                'MOVEMENTCONSUMADD',
                $exception->getMessage()
            ]);
            return redirect(route('movements.create', 'consumables'))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function consumablesUpsave($id, Request $request)
    {
        $input = $request->except('_token');
        $movement = Movement::find($id);
        $oldTransaction = Consumable::find($movement->reference);
        DB::beginTransaction();
        try
        {
            switch ($input['type'])
            {
                case 'incoming':
                    //not the same reference
                    if($input['reference'] != $movement->reference)
                    {
                        if($movement->type != 'incoming')
                        {
                            //revert the changes from the old transaction
                            $revertedQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            DB::table('consumables')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertedQuantity, 'totalPrice' => $revertedPrice]);
                            //new transaction
                            $newReference = Equipment::find($input['reference']);
                            $incomingQuantity = $input['quantity'] + $newReference->actualQuantity;
                            $incomingTotalPrice = ($input['unitPrice'] * $incomingQuantity) + $newReference->totalPrice;
                            DB::table('consumables')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                            //apply new changes to item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $incomingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'consumables',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $incomingTotalPrice
                                ]);
                            $response = 'Item Movement has been Updated!';
                        }
                        else
                        {
                            //revert the changes from the old transaction
                            $revertedQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            DB::table('consumables')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertedQuantity, 'totalPrice' => $revertedPrice]);
                            //new transaction
                            $newReference = Equipment::find($input['reference']);
                            $incomingQuantity = $input['quantity'] + $newReference->actualQuantity;
                            $incomingTotalPrice = ($input['unitPrice'] * $incomingQuantity) + $newReference->totalPrice;
                            DB::table('consumables')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                            //apply new changes to item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $incomingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'consumables',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $incomingTotalPrice
                                ]);
                            $response = 'Item Movement has been Updated!';
                        }
                    }
                    else {
                        //same reference
                        if($movement->type != 'incoming')
                        {
                            //revert the changes
                            $revertedQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            //new quantity and price
                            $incomingQuantity = $input['quantity'] + $revertedQuantity;
                            $incomingTotalPrice = ($input['unitPrice'] * $input['quantity']) + $revertedPrice;
                            DB::table('consumables')->where('id', $input['reference'])->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                            //update item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $incomingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'consumables',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $incomingTotalPrice
                                ]);
                            $response = 'Item Movement has been Updated!';
                        }
                        else
                        {
                            //revert the changes
                            $revertedQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            //new quantity and price
                            $incomingQuantity = $input['quantity'] + $revertedQuantity;
                            $incomingTotalPrice = ($input['unitPrice'] * $input['quantity']) + $revertedPrice;
                            DB::table('consumables')->where('id', $input['reference'])->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                            //update item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $incomingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'consumables',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $incomingTotalPrice
                                ]);
                            $response = 'Item Movement has been Updated!';
                        }
                    }
                    break;
                case 'outgoing':
                    //not the same transaction
                    if($input['reference'] != $movement->reference)
                    {
                        if($movement->type != 'outgoing')
                        {
                            $newReference = Consumable::find($input['reference']);
                            if($newReference->actualQuantity <=0)
                            {
                                $this->notification->create($newReference->id, $newReference->partNumber, 'depleted', $_SESSION['user'], 'consumables', '/resources/consumable/'.$newReference->id);
                                return redirect(route('movements.create', 'consumables'))->with('error', 'Consumable Record has been depleted and we cannot proceed with the item movement!');
                            }
                            //revert the changes from the old transaction
                            $revertQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            DB::table('consumables')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertQuantity, 'totalPrice' => $revertPrice]);
                            //new transaction
                            $outgoingQuantity = $newReference->actualQuantity - $input['quantity'];
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                if($difference != 0)
                                {
                                    $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Consumable Record has been depleted!';
                                }
                                else
                                {
                                    $response = 'Item Movement has been updated!';
                                }
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $outgoingQuantity) + $newReference->totalPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('consumables')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //apply new changes to item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            $fresh_newReference = $newReference->fresh();
                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'critical']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'consumables', '/resources/consumable/'.$fresh_newReference->id);
                            }
                            if($fresh_newReference->actualQuantity == 0)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'consumables', '/resources/consumable/'.$fresh_newReference->id);
                            }
                        }
                        else
                        {
                            $newReference = Consumable::find($input['reference']);
                            if($newReference->actualQuantity <=0)
                            {
                                return redirect(route('movements.create', 'consumables'))->with('error', 'Consumable Record has been depleted and we cannot proceed with the item movement!');
                            }
                            //revert the changes from the old transaction
                            $revertQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            DB::table('consumables')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertQuantity, 'totalPrice' => $revertPrice]);
                            //new transaction

                            $outgoingQuantity = $newReference->actualQuantity - $input['quantity'];
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                if($difference != 0)
                                {
                                    $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Consumable Record has been depleted!';
                                }
                                else
                                {
                                    $response = 'Item Movement has been updated!';
                                }
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $outgoingQuantity) + $newReference->totalPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('consumables')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //apply new changes to item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            $fresh_newReference = $newReference->fresh();
                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'critical']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'consumables', '/resources/consumable/'.$fresh_newReference->id);
                            }
                            if($fresh_newReference->actualQuantity == 0)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'consumables', '/resources/consumable/'.$fresh_newReference->id);
                            }
                        }
                    }
                    else
                    {
                        if($movement->type != 'outgoing')
                        {
                            //revert the changes
                            $revertedQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            //new quantity and price
                            $outgoingQuantity =  $revertedQuantity - $input['quantity'];
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                if($difference != 0)
                                {
                                    $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Consumable Record has been depleted!';
                                }
                                else
                                {
                                    $response = 'Item Movement has been updated!';
                                }
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $input['quantity']) - $revertedPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('consumables')->where('id', $input['reference'])->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //update item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $outgoingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'consumables',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $outgoingTotalPrice
                                ]);
                            $fresh_newReference = Consumable::find($input['reference']);
                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'critical']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'consumables', '/resources/consumable/'.$fresh_newReference->id);
                            }
                            if($fresh_newReference->actualQuantity == 0)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'consumables', '/resources/consumable/'.$fresh_newReference->id);
                            }

                        }
                        else
                        {
                            //revert the changes
                            $revertedQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            //new quantity and price
                            $outgoingQuantity = $revertedQuantity - $input['quantity'] ;
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;

                                $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Consumable Record has been depleted!';
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $input['quantity']) - $revertedPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('consumables')->where('id', $input['reference'])->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //update item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $outgoingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'consumables',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $outgoingTotalPrice
                                ]);
                        }
                        $fresh_newReference = Consumable::find($input['reference']);
                        if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
                        {
                            $fresh_newReference->update(['criticalTagging' => 'critical']);
                            $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'consumables','/resources/consumable/'.$fresh_newReference->id);
                        }
                        if($fresh_newReference->actualQuantity == 0)
                        {
                            $fresh_newReference->update(['criticalTagging' => 'depleted']);
                            $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'consumables', '/resources/consumable/'.$fresh_newReference->id);
                        }
                    }
                    break;
            }

            DB::commit();
            return redirect(route('movements.show', ['consumables', $id]))->with('response', $response);
        }
        catch (\Exception $exception)
        {
            //rollback all db request
            DB::rollBack();
            //create a db error tracker
            $this->util->createError([
                $_SESSION['user'],
                'MOVEMENTCONSUMUPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('movements.show', ['consumables', $id]))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }

    public function componentsCreate(Request $request)
    {
        $reference = Component::find($request->input('reference'));
        if(empty($request))
        {
            return redirect('movements.create', 'components')->with('error', 'Component Record not Found!');
        }
        DB::beginTransaction();
        try
        {
            switch ($request->input('type'))
            {
                case 'incoming':
                    //total quantity if incoming will push through
                    $updateQuantity = intval($reference->actualQuantity) + intval($request->input('quantity'));
                    //total price from the existing record plus incoming
                    $incomingTotalPrice = intval($request->input('quantity') * $request->input('unitPrice'));
                    $finalPrice = (intval($reference->totalPrice) + $incomingTotalPrice);
                    $input = $request->except(['_token']);
                    $input['totalPrice'] = $incomingTotalPrice;
                    $input['dateAdded'] = date('Y-m-d');
                    $input['month'] = date('F', strtotime($input['dateReceived']));
                    $input['year'] = date('Y', strtotime($input['dateReceived']));;
                    $input['origin'] = 'components';
                    $input['entryBy'] = $_SESSION['user'];
                    $id = DB::table('movements')->insertGetId($input);
                    $this->createMovementTracker(
                        $id, $reference->id, $reference->actualQuantity, $updateQuantity, $request->input('type'),
                        $_SESSION['user'], 'components',$reference->totalPrice, $finalPrice
                    );
                    $reference->update([
                        'actualQuantity' => $updateQuantity,
                        'totalPrice' => $finalPrice
                    ]);
                    $response = 'You have recorded an Item Movement!';
                    break;
                case 'outgoing':
                    //total quantity if incoming will push through
                    if($reference->actualQuantity <= 0)
                    {
                        $this->notification->create($reference->id, $reference->partNumber, 'depleted', $_SESSION['user'], 'components', '/resources/component/'.$reference->id);
                        return redirect(route('movements.create', 'components'))->with('error', 'Component item has been depleted and we cannot proceed with the item movement!');
                    }
                    else
                    {
                        $updateQuantity = intval($reference->actualQuantity) - intval($request->input('quantity'));

                        //total price from the existing record plus incoming
                        $outgoingTotalPrice = intval($request->input('quantity') * $request->input('unitPrice'));
                        $finalPrice = (intval($reference->totalPrice) - $outgoingTotalPrice);
                        if($finalPrice < 0) $finalPrice = 0;
                        $input = $request->except(['_token']);
                        if($updateQuantity <= 0 )
                        {
                            $difference = abs($updateQuantity);
                            $updateQuantity = 0;
                            $response = 'Your item movement request went through but we can no longer accommodate '.@$difference.' more since Component item has been depleted!';
                            $input['quantity'] = abs($request->input('quantity') - @$difference);
                        }
                        else
                        {
                            $response = 'Item Move has been recorded!';
                        }
                        $input['totalPrice'] = $outgoingTotalPrice;
                        $input['dateAdded'] = date('Y-m-d');
                        $input['month'] = date('F', strtotime($input['dateReceived']));
                        $input['year'] = date('Y', strtotime($input['dateReceived']));;
                        $input['origin'] = 'components';
                        $input['entryBy'] = $_SESSION['user'];

                        $id = DB::table('movements')->insertGetId($input);
                        $this->createMovementTracker(
                            $id, $reference->id, $reference->actualQuantity, $updateQuantity, $request->input('type'),
                            $_SESSION['user'], 'components',$reference->totalPrice, $finalPrice
                        );
                        $reference->update([
                            'actualQuantity' => $updateQuantity,
                            'totalPrice' => $finalPrice
                        ]);
                    }
                    break;
            }
            $fresh_reference = $reference->fresh();
            if($fresh_reference->actualQuantity <= $fresh_reference->minimumQuantity)
            {
                $fresh_reference->update(['criticalTagging' => 'critical']);
                $this->notification->create($fresh_reference->id, $fresh_reference->partNumber, 'critical', $_SESSION['user'], 'components', '/resources/component/'.$reference->id);
            }
            if($fresh_reference->actualQuantity == 0)
            {
                $fresh_reference->update(['criticalTagging' => 'depleted']);
                $this->notification->create($fresh_reference->id, $fresh_reference->partNumber, 'depleted', $_SESSION['user'], 'components', '/resources/component/'.$reference->id);
            }
            DB::commit();
            return redirect(route('movements.create', 'components'))->with('response', $response);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            $this->util->createError([
                $_SESSION['user'],
                'COMPMOVEMENTADD',
                $exception->getMessage()
            ]);
            return redirect(route('movements.create', 'components'))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function componentsUpsave($id, Request $request)
    {
        $input = $request->except('_token');
        $movement = Movement::find($id);
        $oldTransaction = Component::find($movement->reference);
        DB::beginTransaction();
        try
        {
            switch ($input['type'])
            {
                case 'incoming':
                    //not the same reference
                    if($input['reference'] != $movement->reference)
                    {
                       if($movement->type != 'incoming')
                       {
                           //revert the changes from the old transaction
                           $revertedQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                           $revertedPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                           DB::table('components')
                               ->where('id', $oldTransaction->id)
                               ->update(['actualQuantity' => $revertedQuantity, 'totalPrice' => $revertedPrice]);
                           //new transaction
                           $newReference = Component::find($input['reference']);
                           $incomingQuantity = $input['quantity'] + $newReference->actualQuantity;
                           $incomingTotalPrice = ($input['unitPrice'] * $incomingQuantity) + $newReference->totalPrice;
                           DB::table('components')
                               ->where('id', $input['reference'])
                               ->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                           //apply new changes to item movement record
                           $input['month'] = date('F', strtotime($input['dateReceived']));
                           $input['year'] = date('Y', strtotime($input['dateReceived']));;
                           DB::table('movements')->where('id', $id)->update($input);
                           //update tracker
                           DB::table('movementstracker')
                               ->where('movementRef', $id)
                               ->update([
                                   'previousQuantity' => $revertedQuantity,
                                   'updateQuantity' => $incomingQuantity,
                                   'entryBy' => $_SESSION['user'],
                                   'originGroup' => 'components',
                                   'movementType' => $input['type'],
                                   'prevTotalPrice' => $revertedPrice,
                                   'updateTotalPrice' => $incomingTotalPrice
                               ]);
                           $response = 'Item Movement has been Updated!';
                       }
                       else
                       {
                           //revert the changes from the old transaction
                           $revertedQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                           $revertedPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                           DB::table('components')
                               ->where('id', $oldTransaction->id)
                               ->update(['actualQuantity' => $revertedQuantity, 'totalPrice' => $revertedPrice]);
                           //new transaction
                           $newReference = Component::find($input['reference']);
                           $incomingQuantity = $input['quantity'] + $newReference->actualQuantity;
                           $incomingTotalPrice = ($input['unitPrice'] * $incomingQuantity) + $newReference->totalPrice;
                           DB::table('components')
                               ->where('id', $input['reference'])
                               ->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                           //apply new changes to item movement record
                           $input['month'] = date('F', strtotime($input['dateReceived']));
                           $input['year'] = date('Y', strtotime($input['dateReceived']));;
                           DB::table('movements')->where('id', $id)->update($input);
                           //update tracker
                           DB::table('movementstracker')
                               ->where('movementRef', $id)
                               ->update([
                                   'previousQuantity' => $revertedQuantity,
                                   'updateQuantity' => $incomingQuantity,
                                   'entryBy' => $_SESSION['user'],
                                   'originGroup' => 'components',
                                   'movementType' => $input['type'],
                                   'prevTotalPrice' => $revertedPrice,
                                   'updateTotalPrice' => $incomingTotalPrice
                               ]);
                           $response = 'Item Movement has been Updated!';
                       }
                    }
                    else {
                        //same reference
                       if($movement->type != 'incoming')
                       {
                            //revert the changes
                           $revertedQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                           $revertedPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                           //new quantity and price
                           $incomingQuantity = $input['quantity'] + $revertedQuantity;
                           $incomingTotalPrice = ($input['unitPrice'] * $input['quantity']) + $revertedPrice;
                           DB::table('components')->where('id', $input['reference'])->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                           //update item movement record
                           $input['month'] = date('F', strtotime($input['dateReceived']));
                           $input['year'] = date('Y', strtotime($input['dateReceived']));;
                           DB::table('movements')->where('id', $id)->update($input);
                           //update tracker
                           DB::table('movementstracker')
                               ->where('movementRef', $id)
                               ->update([
                                   'previousQuantity' => $revertedQuantity,
                                   'updateQuantity' => $incomingQuantity,
                                   'entryBy' => $_SESSION['user'],
                                   'originGroup' => 'components',
                                   'movementType' => $input['type'],
                                   'prevTotalPrice' => $revertedPrice,
                                   'updateTotalPrice' => $incomingTotalPrice
                               ]);
                           $response = 'Item Movement has been Updated!';
                       }
                       else
                       {
                           //revert the changes
                           $revertedQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                           $revertedPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                           //new quantity and price
                           $incomingQuantity = $input['quantity'] + $revertedQuantity;
                           $incomingTotalPrice = ($input['unitPrice'] * $input['quantity']) + $revertedPrice;
                           DB::table('components')->where('id', $input['reference'])->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                           //update item movement record
                           $input['month'] = date('F', strtotime($input['dateReceived']));
                           $input['year'] = date('Y', strtotime($input['dateReceived']));;
                           DB::table('movements')->where('id', $id)->update($input);
                           //update tracker
                           DB::table('movementstracker')
                               ->where('movementRef', $id)
                               ->update([
                                   'previousQuantity' => $revertedQuantity,
                                   'updateQuantity' => $incomingQuantity,
                                   'entryBy' => $_SESSION['user'],
                                   'originGroup' => 'components',
                                   'movementType' => $input['type'],
                                   'prevTotalPrice' => $revertedPrice,
                                   'updateTotalPrice' => $incomingTotalPrice
                               ]);
                           $response = 'Item Movement has been Updated!';
                       }
                    }
                    break;
                case 'outgoing':
                    //not the same transaction
                    if($input['reference'] != $movement->reference)
                    {
                        if($movement->type != 'outgoing')
                        {
                            $newReference = Component::find($input['reference']);
                            if($newReference->actualQuantity <=0)
                            {
                                $this->notification->create($newReference->id, $newReference->partNumber, 'depleted', $_SESSION['user'], 'components', '/resources/component/'.$newReference->id);
                                return redirect(route('movements.create', 'components'))->with('error', 'Component item has been depleted and we cannot proceed with the item movement!');
                            }
                            //revert the changes from the old transaction
                            $revertQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            DB::table('components')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertQuantity, 'totalPrice' => $revertPrice]);
                            //new transaction

                            $outgoingQuantity = $newReference->actualQuantity - $input['quantity'];
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Component Record has been depleted!';
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $outgoingQuantity) + $newReference->totalPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('components')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //apply new changes to item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            $fresh_newReference = $newReference->fresh();
                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'critical']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'components', '/resources/component/'.$newReference->id);
                            }
                            if($fresh_newReference->actualQuantity == 0)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'components' ,'/resources/component/'.$newReference->id);
                            }
                        }
                        else
                        {
                            $newReference = Component::find($input['reference']);
                            if($newReference->actualQuantity <=0)
                            {
                                return redirect(route('movements.create', 'components'))->with('error', 'Component item has been depleted and we cannot proceed with the item movement!');
                            }
                            //revert the changes from the old transaction
                            $revertQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            DB::table('components')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertQuantity, 'totalPrice' => $revertPrice]);
                            //new transaction

                            $outgoingQuantity = $newReference->actualQuantity - $input['quantity'];
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Component Record has been depleted!';
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $outgoingQuantity) + $newReference->totalPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('components')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //apply new changes to item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            $fresh_newReference = Component::find($input['reference']);
                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'critical']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'components', '/resources/component/'.$fresh_newReference->id);
                            }
                            if($fresh_newReference->actualQuantity == 0)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'components', '/resources/component/'.$fresh_newReference->id);
                            }
                        }
                    }
                    else
                    {
                        if($movement->type != 'outgoing')
                        {
                                //revert the changes
                                $revertedQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                                $revertedPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                                //new quantity and price
                                $outgoingQuantity =  $revertedQuantity - $input['quantity'];
                                if($outgoingQuantity <= 0)
                                {
                                    //get the difference
                                    $difference = abs($outgoingQuantity);
                                    //update the quantity that can only be accomodated
                                    $input['quantity'] = $input['quantity'] - $difference;
                                    //to zero since negative should be also zero
                                    $outgoingQuantity = 0;
                                    $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Component Record has been depleted!';
                                }
                                else
                                {
                                    $response = 'Item Movement has been updated!';
                                }
                                $outgoingTotalPrice = ($input['unitPrice'] * $input['quantity']) - $revertedPrice;
                                if($outgoingTotalPrice <= 0)
                                {
                                    $outgoingTotalPrice = 0;
                                }
                                DB::table('components')->where('id', $input['reference'])->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                                //update item movement record
                                $input['month'] = date('F', strtotime($input['dateReceived']));
                                $input['year'] = date('Y', strtotime($input['dateReceived']));;
                                DB::table('movements')->where('id', $id)->update($input);
                                //update tracker
                                DB::table('movementstracker')
                                    ->where('movementRef', $id)
                                    ->update([
                                        'previousQuantity' => $revertedQuantity,
                                        'updateQuantity' => $outgoingQuantity,
                                        'entryBy' => $_SESSION['user'],
                                        'originGroup' => 'component',
                                        'movementType' => $input['type'],
                                        'prevTotalPrice' => $revertedPrice,
                                        'updateTotalPrice' => $outgoingTotalPrice
                                    ]);
                            $fresh_newReference = Component::find($input['reference']);
                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'critical']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'components', '/resources/component/'.$fresh_newReference->id);
                            }
                            if($fresh_newReference->actualQuantity == 0)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'components', '/resources/component/'.$fresh_newReference->id);
                            }
                        }
                        else
                        {
                                //revert the changes
                                $revertedQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                                $revertedPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                                //new quantity and price
                                $outgoingQuantity = $revertedQuantity - $input['quantity'] ;
                                if($outgoingQuantity <= 0)
                                {
                                    //get the difference
                                    $difference = abs($outgoingQuantity);
                                    //update the quantity that can only be accomodated
                                    $input['quantity'] = $input['quantity'] - $difference;
                                    //to zero since negative should be also zero
                                    $outgoingQuantity = 0;
                                    $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Component Record has been depleted!';
                                }
                                else
                                {
                                    $response = 'Item Movement has been updated!';
                                }
                                $outgoingTotalPrice = ($input['unitPrice'] * $input['quantity']) - $revertedPrice;
                                if($outgoingTotalPrice <= 0)
                                {
                                    $outgoingTotalPrice = 0;
                                }
                                DB::table('components')->where('id', $input['reference'])->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                                //update item movement record
                                $input['month'] = date('F', strtotime($input['dateReceived']));
                                $input['year'] = date('Y', strtotime($input['dateReceived']));;
                                DB::table('movements')->where('id', $id)->update($input);
                                //update tracker
                                DB::table('movementstracker')
                                    ->where('movementRef', $id)
                                    ->update([
                                        'previousQuantity' => $revertedQuantity,
                                        'updateQuantity' => $outgoingQuantity,
                                        'entryBy' => $_SESSION['user'],
                                        'originGroup' => 'components',
                                        'movementType' => $input['type'],
                                        'prevTotalPrice' => $revertedPrice,
                                        'updateTotalPrice' => $outgoingTotalPrice
                                    ]);

                            $fresh_newReference = Component::find($input['reference']);
                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'critical']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'components', '/resources/component/'.$fresh_newReference->id);
                            }
                            if($fresh_newReference->actualQuantity == 0)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'components', '/resources/component/'.$fresh_newReference->id);
                            }
                        }
                    }
                    break;
            }
            DB::commit();
            return redirect(route('movements.show', ['components', $id]))->with('response', $response);
        }
        catch (\Exception $exception)
        {
            //rollback all db request
            DB::rollBack();
            //create a db error tracker
            $this->util->createError([
                $_SESSION['user'],
                'MOVEMENTCOMPUPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('movements.show', ['components', $id]))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }

    public function consignedCreate(Request $request)
    {
        $reference = Consigned::find($request->input('reference'));
        if(empty($request))
        {
            return redirect('movements.create', 'consigned')->with('error', 'Consigned Record not Found!');
        }
        DB::beginTransaction();
        try
        {
            switch ($request->input('type'))
            {
                case 'incoming':
                    //total quantity if incoming will push through
                    $updateQuantity = intval($reference->actualQuantity) + intval($request->input('quantity'));
                    //total price from the existing record plus incoming
                    $incomingTotalPrice = intval($request->input('quantity') * $request->input('unitPrice'));
                    $finalPrice = (intval($reference->totalPrice) + $incomingTotalPrice);
                    $input = $request->except(['_token']);
                    $input['totalPrice'] = $incomingTotalPrice;
                    $input['dateAdded'] = date('Y-m-d');
                    $input['month'] = date('F', strtotime($input['dateReceived']));
                    $input['year'] = date('Y', strtotime($input['dateReceived']));;
                    $input['origin'] = 'consigned';
                    $input['entryBy'] = $_SESSION['user'];
                    $id = DB::table('movements')->insertGetId($input);
                    $this->createMovementTracker(
                        $id, $reference->id, $reference->actualQuantity, $updateQuantity, $request->input('type'),
                        $_SESSION['user'], 'consigned',$reference->totalPrice, $finalPrice
                    );
                    $reference->update([
                        'actualQuantity' => $updateQuantity,
                        'totalPrice' => $finalPrice
                    ]);
                    $response = 'You have recorded an Item Movement!';
                    break;
                case 'outgoing':
                    //total quantity if incoming will push through
                    if($reference->actualQuantity <= 0)
                    {
//                        $this->notification->create($reference->id, $reference->partNumber, 'depleted', $_SESSION['user'], 'consigned');
                        return redirect(route('movements.create', 'consigned'))->with('error', 'Consigned item has been depleted and we cannot proceed with the item movement!');
                    }
                    else
                    {
                        $updateQuantity = intval($reference->actualQuantity) - intval($request->input('quantity'));


                        //total price from the existing record plus incoming
                        $outgoingTotalPrice = intval($request->input('quantity') * $request->input('unitPrice'));
                        $finalPrice = (intval($reference->totalPrice) - $outgoingTotalPrice);
                        if($finalPrice < 0) $finalPrice = 0;
                        $input = $request->except(['_token']);
                        if($updateQuantity <= 0 )
                        {
                            $difference = abs($updateQuantity);
                            $updateQuantity = 0;
                            $response = 'Your item movement request went through but we can no longer accommodate '.@$difference.' more since Consigned item has been depleted!';
                            $input['quantity'] = abs($request->input('quantity') - @$difference);
                        }
                        else
                        {
                            $response = 'Item Move has been recorded!';
                        }
                        $input['totalPrice'] = $outgoingTotalPrice;
                        $input['dateAdded'] = date('Y-m-d');
                        $input['month'] = date('F', strtotime($input['dateReceived']));
                        $input['year'] = date('Y', strtotime($input['dateReceived']));;
                        $input['origin'] = 'consigned';
                        $input['entryBy'] = $_SESSION['user'];

                        $id = DB::table('movements')->insertGetId($input);
                        $this->createMovementTracker(
                            $id, $reference->id, $reference->actualQuantity, $updateQuantity, $request->input('type'),
                            $_SESSION['user'], 'consigned',$reference->totalPrice, $finalPrice
                        );
                        $reference->update([
                            'actualQuantity' => $updateQuantity,
                            'totalPrice' => $finalPrice
                        ]);
                    }
                    break;
            }
//            $fresh_reference = $reference->fresh();
//            if($fresh_reference->actualQuantity <= $fresh_reference->minimumQuantity)
//            {
//                $fresh_reference->update(['criticalTagging' => 'critical']);
//                $this->notification->create($fresh_reference->id, $fresh_reference->partNumber, 'critical', $_SESSION['user'], 'consigned');
//            }
//            if($fresh_reference->actualQuantity == 0)
//            {
//                $fresh_reference->update(['criticalTagging' => 'depleted']);
//                $this->notification->create($fresh_reference->id, $fresh_reference->partNumber, 'depleted', $_SESSION['user'], 'consigned');
//            }
            DB::commit();
            return redirect(route('movements.create', 'consigned'))->with('response', $response);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            $this->util->createError([
                $_SESSION['user'],
                'MOVEMENTADD',
                $exception->getMessage()
            ]);
            return redirect(route('movements.create', 'consigned'))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function consignedUpsave($id, Request $request)
    {
        $input = $request->except('_token');
        $movement = Movement::find($id);
        $oldTransaction = Consigned::find($movement->reference);
        DB::beginTransaction();
        try
        {
            switch ($input['type'])
            {
                case 'incoming':
                    //not the same reference
                    if($input['reference'] != $movement->reference)
                    {
                        if($movement->type != 'incoming')
                        {
                            //revert the changes from the old transaction
                            $revertedQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            DB::table('consignedspares')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertedQuantity, 'totalPrice' => $revertedPrice]);
                            //new transaction
                            $newReference = Consigned::find($input['reference']);
                            $incomingQuantity = $input['quantity'] + $newReference->actualQuantity;
                            $incomingTotalPrice = ($input['unitPrice'] * $incomingQuantity) + $newReference->totalPrice;
                            DB::table('consignedspares')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                            //apply new changes to item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $incomingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'consigned',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $incomingTotalPrice
                                ]);
                            $response = 'Item Movement has been Updated!';
                        }
                        else
                        {
                            //revert the changes from the old transaction
                            $revertedQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            DB::table('consignedspares')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertedQuantity, 'totalPrice' => $revertedPrice]);
                            //new transaction
                            $newReference = Consigned::find($input['reference']);
                            $incomingQuantity = $input['quantity'] + $newReference->actualQuantity;
                            $incomingTotalPrice = ($input['unitPrice'] * $incomingQuantity) + $newReference->totalPrice;
                            DB::table('consignedspares')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                            //apply new changes to item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $incomingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'consigned',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $incomingTotalPrice
                                ]);
                            $response = 'Item Movement has been Updated!';
                        }
                    }
                    else {
                        //same reference
                        if($movement->type != 'incoming')
                        {
                            //revert the changes
                            $revertedQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            //new quantity and price
                            $incomingQuantity = $input['quantity'] + $revertedQuantity;
                            $incomingTotalPrice = ($input['unitPrice'] * $input['quantity']) + $revertedPrice;
                            DB::table('consignedspares')->where('id', $input['reference'])->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                            //update item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $incomingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'consigned',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $incomingTotalPrice
                                ]);
                            $response = 'Item Movement has been Updated!';
                        }
                        else
                        {
                            //revert the changes
                            $revertedQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            //new quantity and price
                            $incomingQuantity = $input['quantity'] + $revertedQuantity;
                            $incomingTotalPrice = ($input['unitPrice'] * $input['quantity']) + $revertedPrice;
                            DB::table('consignedspares')->where('id', $input['reference'])->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                            //update item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $incomingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'consigned',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $incomingTotalPrice
                                ]);
                            $response = 'Item Movement has been Updated!';
                        }
                    }
                    break;
                case 'outgoing':
                    //not the same transaction
                    if($input['reference'] != $movement->reference)
                    {
                        if($movement->type != 'outgoing')
                        {
                            $newReference = Consigned::find($input['reference']);
                            if($newReference->actualQuantity <=0)
                            {
//                                $this->notification->create($newReference->id, $newReference->partNumber, 'depleted', $_SESSION['user'], 'consigned');
                                return redirect(route('movements.create', 'components'))->with('error', 'Consigned Spare has been depleted and we cannot proceed with the item movement!');
                            }
                            //revert the changes from the old transaction
                            $revertQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            DB::table('consignedspares')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertQuantity, 'totalPrice' => $revertPrice]);
                            //new transaction

                            $outgoingQuantity = $newReference->actualQuantity - $input['quantity'];
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Consigned Spare Record has been depleted!';
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $outgoingQuantity) + $newReference->totalPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('consignedspares')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //apply new changes to item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
//                            $fresh_newReference = $newReference->fresh();
//                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
//                            {
//                                $fresh_newReference->update(['criticalTagging' => 'critical']);
//                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'consigned');
//                            }
//                            if($fresh_newReference->actualQuantity == 0)
//                            {
//                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
//                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'consigned');
//                            }
                        }
                        else
                        {
                            $newReference = Consigned::find($input['reference']);
                            if($newReference->actualQuantity <=0)
                            {
                                return redirect(route('movements.create', 'consigned'))->with('error', 'Consigned Spare has been depleted and we cannot proceed with the item movement!');
                            }
                            //revert the changes from the old transaction
                            $revertQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            DB::table('consignedspares')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertQuantity, 'totalPrice' => $revertPrice]);
                            //new transaction

                            $outgoingQuantity = $newReference->actualQuantity - $input['quantity'];
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Consigned Record has been depleted!';
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $outgoingQuantity) + $newReference->totalPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('consignedspares')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //apply new changes to item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
//                            $fresh_newReference = $newReference->fresh();
//                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
//                            {
//                                $fresh_newReference->update(['criticalTagging' => 'critical']);
//                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'consigned');
//                            }
//                            if($fresh_newReference->actualQuantity == 0)
//                            {
//                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
//                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'consigned');
//                            }
                        }
                    }
                    else
                    {
                        if($movement->type != 'outgoing')
                        {
                            //revert the changes
                            $revertedQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            //new quantity and price
                            $outgoingQuantity =  $revertedQuantity - $input['quantity'];
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Consigned Spare has been depleted!';
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $input['quantity']) - $revertedPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('consignedspares')->where('id', $input['reference'])->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //update item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $outgoingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'consigned',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $outgoingTotalPrice
                                ]);
//                            $fresh_newReference = Consigned::find($input['reference']);
//                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
//                            {
//                                $fresh_newReference->update(['criticalTagging' => 'critical']);
//                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'consigned');
//                            }
//                            if($fresh_newReference->actualQuantity == 0)
//                            {
//                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
//                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'consigned');
//                            }
                        }
                        else
                        {
                            //revert the changes
                            $revertedQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            //new quantity and price
                            $outgoingQuantity = $revertedQuantity - $input['quantity'] ;
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Consigned Spare has been depleted!';
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $input['quantity']) - $revertedPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('consignedspares')->where('id', $input['reference'])->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //update item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $outgoingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'consigned',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $outgoingTotalPrice
                                ]);
//                            $fresh_newReference = Consigned::find($input['reference']);
//                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
//                            {
//                                $fresh_newReference->update(['criticalTagging' => 'critical']);
//                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'consigned');
//                            }
//                            if($fresh_newReference->actualQuantity == 0)
//                            {
//                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
//                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'consigned');
//                            }
                        }
                    }
                    break;
            }
            DB::commit();
            return redirect(route('movements.show', ['consigned', $id]))->with('response', $response);
        }
        catch (\Exception $exception)
        {
            //rollback all db request
            DB::rollBack();
            //create a db error tracker
            $this->util->createError([
                $_SESSION['user'],
                'MOVEMENTCONSUPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('movements.show', ['consigned', $id]))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }

    public function equipmentCreate(Request $request)
    {
        $reference = Equipment::find($request->input('reference'));
        if(empty($request))
        {
            return redirect('movements.create', 'equipment')->with('error', 'Equipment Record not Found!');
        }
        DB::beginTransaction();
        try
        {
            switch ($request->input('type'))
            {
                case 'incoming':
                    //total quantity if incoming will push through
                    $updateQuantity = intval($reference->actualQuantity) + intval($request->input('quantity'));
                    //total price from the existing record plus incoming
                    $incomingTotalPrice = intval($request->input('quantity') * $request->input('unitPrice'));
                    $finalPrice = (intval($reference->totalPrice) + $incomingTotalPrice);
                    $input = $request->except(['_token']);
                    $input['totalPrice'] = $incomingTotalPrice;
                    $input['dateAdded'] = date('Y-m-d');
                    $input['month'] = date('F', strtotime($input['dateReceived']));
                    $input['year'] = date('Y', strtotime($input['dateReceived']));;
                    $input['origin'] = 'equipment';
                    $input['entryBy'] = $_SESSION['user'];
                    $id = DB::table('movements')->insertGetId($input);
                    $this->createMovementTracker(
                        $id, $reference->id, $reference->actualQuantity, $updateQuantity, $request->input('type'),
                        $_SESSION['user'], 'equipment',$reference->totalPrice, $finalPrice
                    );
                    $reference->update([
                        'actualQuantity' => $updateQuantity,
                        'totalPrice' => $finalPrice
                    ]);
                    $response = 'You have recorded an Item Movement!';
                    break;
                case 'outgoing':
                    //total quantity if incoming will push through
                    if($reference->actualQuantity <= 0)
                    {
                        $this->notification->create($reference->id, $reference->partNumber, 'depleted', $_SESSION['user'], 'equipment', '/resources/equipment/'.$reference->id);
                        return redirect(route('movements.create', 'equipment'))->with('error', 'Equipment item has been depleted and we cannot proceed with the item movement!');
                    }
                    else
                    {
                        $updateQuantity = intval($reference->actualQuantity) - intval($request->input('quantity'));

                        //total price from the existing record plus incoming
                        $outgoingTotalPrice = intval($request->input('quantity') * $request->input('unitPrice'));
                        $finalPrice = (intval($reference->totalPrice) - $outgoingTotalPrice);
                        if($finalPrice < 0) $finalPrice = 0;
                        $input = $request->except(['_token']);
                        if($updateQuantity <= 0 )
                        {
                            $difference = abs($updateQuantity);
                            $updateQuantity = 0;
                            $input['quantity'] = abs($request->input('quantity') - @$difference);
                            if($difference != 0)
                            {
                                $response = 'Your item movement request went through but we can no longer accommodate '.@$difference.' more since Equipment item has been depleted!';
                            }
                            else
                            {
                                $response = 'Item Move has been recorded!';
                            }
                        }
                        else
                        {
                            $response = 'Item Move has been recorded!';
                        }
                        $input['totalPrice'] = $outgoingTotalPrice;
                        $input['dateAdded'] = date('Y-m-d');
                        $input['month'] = date('F', strtotime($input['dateReceived']));
                        $input['year'] = date('Y', strtotime($input['dateReceived']));;
                        $input['origin'] = 'equipment';
                        $input['entryBy'] = $_SESSION['user'];

                        $id = DB::table('movements')->insertGetId($input);
                        $this->createMovementTracker(
                            $id, $reference->id, $reference->actualQuantity, $updateQuantity, $request->input('type'),
                            $_SESSION['user'], 'equipment',$reference->totalPrice, $finalPrice
                        );
                        $reference->update([
                            'actualQuantity' => $updateQuantity,
                            'totalPrice' => $finalPrice
                        ]);
                    }
                    break;
            }
            $fresh_reference = $reference->fresh();
            if($fresh_reference->actualQuantity <= $fresh_reference->minimumQuantity)
            {
                $fresh_reference->update(['criticalTagging' => 'critical']);
                $this->notification->create($fresh_reference->id, $fresh_reference->partNumber, 'critical', $_SESSION['user'], 'equipment', '/resources/equipment/'.$reference->id);
            }
            if($fresh_reference->actualQuantity == 0)
            {
                $fresh_reference->update(['criticalTagging' => 'depleted']);
                $this->notification->create($fresh_reference->id, $fresh_reference->partNumber, 'depleted', $_SESSION['user'], 'equipment', '/resources/equipment/'.$reference->id);
            }
            DB::commit();
            return redirect(route('movements.create', 'equipment'))->with('response', $response);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            $this->util->createError([
                $_SESSION['user'],
                'MOVEMENTEQADD',
                $exception->getMessage()
            ]);
            return redirect(route('movements.create', 'equipment'))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function equipmentUpsave($id, Request $request)
    {
        $input = $request->except('_token');
        $movement = Movement::find($id);
        $oldTransaction = Equipment::find($movement->reference);
        DB::beginTransaction();
        try
        {
            switch ($input['type'])
            {
                case 'incoming':
                    //not the same reference
                    if($input['reference'] != $movement->reference)
                    {
                        if($movement->type != 'incoming')
                        {
                            //revert the changes from the old transaction
                            $revertedQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            DB::table('equipment')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertedQuantity, 'totalPrice' => $revertedPrice]);
                            //new transaction
                            $newReference = Equipment::find($input['reference']);
                            $incomingQuantity = $input['quantity'] + $newReference->actualQuantity;
                            $incomingTotalPrice = ($input['unitPrice'] * $incomingQuantity) + $newReference->totalPrice;
                            DB::table('equipment')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                            //apply new changes to item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $incomingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'equipment',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $incomingTotalPrice
                                ]);
                            $response = 'Item Movement has been Updated!';
                        }
                        else
                        {
                            //revert the changes from the old transaction
                            $revertedQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            DB::table('equipment')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertedQuantity, 'totalPrice' => $revertedPrice]);
                            //new transaction
                            $newReference = Equipment::find($input['reference']);
                            $incomingQuantity = $input['quantity'] + $newReference->actualQuantity;
                            $incomingTotalPrice = ($input['unitPrice'] * $incomingQuantity) + $newReference->totalPrice;
                            DB::table('equipment')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                            //apply new changes to item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $incomingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'equipment',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $incomingTotalPrice
                                ]);
                            $response = 'Item Movement has been Updated!';
                        }
                    }
                    else {
                        //same reference
                        if($movement->type != 'incoming')
                        {
                            //revert the changes
                            $revertedQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            //new quantity and price
                            $incomingQuantity = $input['quantity'] + $revertedQuantity;
                            $incomingTotalPrice = ($input['unitPrice'] * $input['quantity']) + $revertedPrice;
                            DB::table('equipment')->where('id', $input['reference'])->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                            //update item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $incomingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'equipment',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $incomingTotalPrice
                                ]);
                            $response = 'Item Movement has been Updated!';
                        }
                        else
                        {
                            //revert the changes
                            $revertedQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            //new quantity and price
                            $incomingQuantity = $input['quantity'] + $revertedQuantity;
                            $incomingTotalPrice = ($input['unitPrice'] * $input['quantity']) + $revertedPrice;
                            DB::table('equipment')->where('id', $input['reference'])->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                            //update item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $incomingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'equipment',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $incomingTotalPrice
                                ]);
                            $response = 'Item Movement has been Updated!';
                        }
                    }
                    break;
                case 'outgoing':
                    //not the same transaction
                    if($input['reference'] != $movement->reference)
                    {
                        if($movement->type != 'outgoing')
                        {
                            $newReference = Equipment::find($input['reference']);
                            if($newReference->actualQuantity <=0)
                            {
                                $this->notification->create($newReference->id, $newReference->partNumber, 'depleted', $_SESSION['user'], 'equipment', '/resources/equipment/'.$newReference->id);
                                return redirect(route('movements.create', 'equipment'))->with('error', 'Equipment Record has been depleted and we cannot proceed with the item movement!');
                            }
                            //revert the changes from the old transaction
                            $revertQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            DB::table('equipment')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertQuantity, 'totalPrice' => $revertPrice]);
                            //new transaction

                            $outgoingQuantity = $newReference->actualQuantity - $input['quantity'];
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                if($difference != 0)
                                {
                                    $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Equipment Record has been depleted!';
                                }
                                else
                                {
                                    $response = 'Item Movement has been updated!';
                                }
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $outgoingQuantity) + $newReference->totalPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('equipment')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //apply new changes to item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            $fresh_newReference = $newReference->fresh();
                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'critical']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'equipment', '/resources/equipment/'.$fresh_newReference->id);
                            }
                            if($fresh_newReference->actualQuantity == 0)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'equipment', '/resources/equipment/'.$fresh_newReference->id);
                            }
                        }
                        else
                        {
                            $newReference = Equipment::find($input['reference']);
                            if($newReference->actualQuantity <=0)
                            {
                                return redirect(route('movements.create', 'equipment'))->with('error', 'Equipment Record has been depleted and we cannot proceed with the item movement!');
                            }
                            //revert the changes from the old transaction
                            $revertQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            DB::table('equipment')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertQuantity, 'totalPrice' => $revertPrice]);
                            //new transaction

                            $outgoingQuantity = $newReference->actualQuantity - $input['quantity'];
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                if($difference != 0)
                                {
                                    $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Equipment Record has been depleted!';
                                }
                                else
                                {
                                    $response = 'Item Movement has been updated!';
                                }
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $outgoingQuantity) + $newReference->totalPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('equipment')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //apply new changes to item movement record
                            DB::table('movements')->where('id', $id)->update($input);
                            $fresh_newReference = $newReference->fresh();
                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'critical']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'equipment', '/resources/equipment/'.$fresh_newReference->id);
                            }
                            if($fresh_newReference->actualQuantity == 0)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'equipment', '/resources/equipment/'.$fresh_newReference->id);
                            }
                        }
                    }
                    else
                    {
                        if($movement->type != 'outgoing')
                        {
                            //revert the changes
                            $revertedQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            //new quantity and price
                            $outgoingQuantity =  $revertedQuantity - $input['quantity'];
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                if($difference != 0)
                                {
                                    $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Equipment Record has been depleted!';
                                }
                                else
                                {
                                    $response = 'Item Movement has been updated!';
                                }
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $input['quantity']) - $revertedPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('equipment')->where('id', $input['reference'])->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //update item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $outgoingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'equipment',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $outgoingTotalPrice
                                ]);
                            $fresh_newReference = Equipment::find($input['reference']);
                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'critical']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'equipment', '/resources/equipment/'.$fresh_newReference->id);
                            }
                            if($fresh_newReference->actualQuantity == 0)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'equipment', '/resources/equipment/'.$fresh_newReference->id);
                            }
                        }
                        else
                        {
                            //revert the changes
                            $revertedQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            //new quantity and price
                            $outgoingQuantity = $revertedQuantity - $input['quantity'] ;
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Equipment Record has been depleted!';
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $input['quantity']) - $revertedPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('equipment')->where('id', $input['reference'])->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //update item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $outgoingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'equipment',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $outgoingTotalPrice
                                ]);
                            $fresh_newReference = Equipment::find($input['reference']);
                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'critical']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'equipment', '/resources/equipment/'.$fresh_newReference->id);
                            }
                            if($fresh_newReference->actualQuantity == 0)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'equipment', '/resources/equipment/'.$fresh_newReference->id);
                            }
                        }
                    }
                    break;
            }
            DB::commit();
            return redirect(route('movements.show', ['equipment', $id]))->with('response', $response);
        }
        catch (\Exception $exception)
        {
            //rollback all db request
            DB::rollBack();
            //create a db error tracker
            $this->util->createError([
                $_SESSION['user'],
                'MOVEMENTEQUPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('movements.show', ['equipment', $id]))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }

    public function toolsCreate(Request $request)
    {
        $reference = Tool::find($request->input('reference'));
        if(empty($request))
        {
            return redirect('movements.create', 'tools')->with('error', 'Tool Record not Found!');
        }
        DB::beginTransaction();
        try
        {
            switch ($request->input('type'))
            {
                case 'incoming':
                    //total quantity if incoming will push through
                    $updateQuantity = intval($reference->actualQuantity) + intval($request->input('quantity'));
                    //total price from the existing record plus incoming
                    $incomingTotalPrice = intval($request->input('quantity') * $request->input('unitPrice'));
                    $finalPrice = (intval($reference->totalPrice) + $incomingTotalPrice);
                    $input = $request->except(['_token']);
                    $input['totalPrice'] = $incomingTotalPrice;
                    $input['dateAdded'] = date('Y-m-d');
                    $input['month'] = date('F', strtotime($input['dateReceived']));
                    $input['year'] = date('Y', strtotime($input['dateReceived']));;
                    $input['origin'] = 'tools';
                    $input['entryBy'] = $_SESSION['user'];
                    $id = DB::table('movements')->insertGetId($input);
                    $this->createMovementTracker(
                        $id, $reference->id, $reference->actualQuantity, $updateQuantity, $request->input('type'),
                        $_SESSION['user'], 'tools',$reference->totalPrice, $finalPrice
                    );
                    $reference->update([
                        'actualQuantity' => $updateQuantity,
                        'totalPrice' => $finalPrice
                    ]);
                    $response = 'You have recorded an Item Movement!';
                    break;
                case 'outgoing':
                    //total quantity if incoming will push through
                    if($reference->actualQuantity <= 0)
                    {
                        $this->notification->create($reference->id, $reference->partNumber, 'depleted', $_SESSION['user'], 'tools', '/resources/tool/'.$reference->id);
                        return redirect(route('movements.create', 'tools'))->with('error', 'Tool item has been depleted and we cannot proceed with the item movement!');
                    }
                    else
                    {
                        $updateQuantity = intval($reference->actualQuantity) - intval($request->input('quantity'));


                        //total price from the existing record plus incoming
                        $outgoingTotalPrice = intval($request->input('quantity') * $request->input('unitPrice'));
                        $finalPrice = (intval($reference->totalPrice) - $outgoingTotalPrice);
                        if($finalPrice < 0) $finalPrice = 0;
                        $input = $request->except(['_token']);
                        if($updateQuantity <= 0 )
                        {
                            $difference = abs($updateQuantity);
                            $updateQuantity = 0;
                            $input['quantity'] = abs($request->input('quantity') - @$difference);
                            if($difference != 0)
                            {
                                $response = 'Your item movement request went through but we can no longer accommodate '.@$difference.' more since Tool item has been depleted!';
                            }
                            else
                            {
                                $response = 'Item Move has been recorded!';
                            }
                        }
                        else
                        {
                            $response = 'Item Move has been recorded!';
                        }
                        $input['totalPrice'] = $outgoingTotalPrice;
                        $input['dateAdded'] = date('Y-m-d');
                        $input['month'] = date('F', strtotime($input['dateReceived']));
                        $input['year'] = date('Y', strtotime($input['dateReceived']));;
                        $input['origin'] = 'tools';
                        $input['entryBy'] = $_SESSION['user'];

                        $id = DB::table('movements')->insertGetId($input);
                        $this->createMovementTracker(
                            $id, $reference->id, $reference->actualQuantity, $updateQuantity, $request->input('type'),
                            $_SESSION['user'], 'tools',$reference->totalPrice, $finalPrice
                        );
                        $reference->update([
                            'actualQuantity' => $updateQuantity,
                            'totalPrice' => $finalPrice
                        ]);
                    }
                    break;
            }
            $fresh_reference = $reference->fresh();
            if($fresh_reference->actualQuantity <= $fresh_reference->minimumQuantity)
            {
                $fresh_reference->update(['criticalTagging' => 'critical']);
                $this->notification->create($fresh_reference->id, $fresh_reference->partNumber, 'critical', $_SESSION['user'], 'tools', '/resources/tool/'.$reference->id);
            }
            if($fresh_reference->actualQuantity == 0)
            {
                $fresh_reference->update(['criticalTagging' => 'depleted']);
                $this->notification->create($fresh_reference->id, $fresh_reference->partNumber, 'depleted', $_SESSION['user'], 'tools', '/resources/tool/'.$reference->id);
            }
            DB::commit();
            return redirect(route('movements.create', 'tools'))->with('response', $response);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            $this->util->createError([
                $_SESSION['user'],
                'MOVEMENTTOOLADD',
                $exception->getMessage()
            ]);
            return redirect(route('movements.create', 'tools'))->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function toolsUpsave($id, Request $request)
    {
        $input = $request->except('_token');
        $movement = Movement::find($id);
        $oldTransaction = Tool::find($movement->reference);
        DB::beginTransaction();
        try
        {
            switch ($input['type'])
            {
                case 'incoming':
                    //not the same reference
                    if($input['reference'] != $movement->reference)
                    {
                        if($movement->type != 'incoming')
                        {
                            //revert the changes from the old transaction
                            $revertedQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            DB::table('tools')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertedQuantity, 'totalPrice' => $revertedPrice]);
                            //new transaction
                            $newReference = Tool::find($input['reference']);
                            $incomingQuantity = $input['quantity'] + $newReference->actualQuantity;
                            $incomingTotalPrice = ($input['unitPrice'] * $incomingQuantity) + $newReference->totalPrice;
                            DB::table('tools')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                            //apply new changes to item movement record
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $incomingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'tools',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $incomingTotalPrice
                                ]);
                            $response = 'Item Movement has been Updated!';
                        }
                        else
                        {
                            //revert the changes from the old transaction
                            $revertedQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            DB::table('tools')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertedQuantity, 'totalPrice' => $revertedPrice]);
                            //new transaction
                            $newReference = Tool::find($input['reference']);
                            $incomingQuantity = $input['quantity'] + $newReference->actualQuantity;
                            $incomingTotalPrice = ($input['unitPrice'] * $incomingQuantity) + $newReference->totalPrice;
                            DB::table('tools')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                            //apply new changes to item movement record
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $incomingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'tools',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $incomingTotalPrice
                                ]);
                            $response = 'Item Movement has been Updated!';
                        }
                    }
                    else {
                        //same reference
                        if($movement->type != 'incoming')
                        {
                            //revert the changes
                            $revertedQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            //new quantity and price
                            $incomingQuantity = $input['quantity'] + $revertedQuantity;
                            $incomingTotalPrice = ($input['unitPrice'] * $input['quantity']) + $revertedPrice;
                            DB::table('tools')->where('id', $input['reference'])->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                            //update item movement record
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $incomingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'tools',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $incomingTotalPrice
                                ]);
                            $response = 'Item Movement has been Updated!';
                        }
                        else
                        {
                            //revert the changes
                            $revertedQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            //new quantity and price
                            $incomingQuantity = $input['quantity'] + $revertedQuantity;
                            $incomingTotalPrice = ($input['unitPrice'] * $input['quantity']) + $revertedPrice;
                            DB::table('tools')->where('id', $input['reference'])->update(['actualQuantity' => $incomingQuantity, 'totalPrice' => $incomingTotalPrice]);
                            //update item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $incomingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'tools',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $incomingTotalPrice
                                ]);
                            $response = 'Item Movement has been Updated!';
                        }
                    }
                    break;
                case 'outgoing':
                    //not the same transaction
                    if($input['reference'] != $movement->reference)
                    {
                        if($movement->type != 'outgoing')
                        {
                            $newReference = Tool::find($input['reference']);
                            if($newReference->actualQuantity <=0)
                            {
                                $this->notification->create($newReference->id, $newReference->partNumber, 'depleted', $_SESSION['user'], 'tools', '/resources/tool/'.$newReference->id);
                                return redirect(route('movements.create', 'tools'))->with('error', 'Tools Record has been depleted and we cannot proceed with the item movement!');
                            }
                            //revert the changes from the old transaction
                            $revertQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            DB::table('tools')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertQuantity, 'totalPrice' => $revertPrice]);
                            //new transaction

                            $outgoingQuantity = $newReference->actualQuantity - $input['quantity'];
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                if($difference != 0)
                                {
                                    $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Tools Record has been depleted!';
                                }
                                else
                                {
                                    $response = 'Item Movement has been updated!';
                                }
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $outgoingQuantity) + $newReference->totalPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('tools')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //apply new changes to item movement record
                            DB::table('movements')->where('id', $id)->update($input);
                            $fresh_newReference = $newReference->fresh();
                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'critical']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'tools', '/resources/tool/'.$fresh_newReference->id);
                            }
                            if($fresh_newReference->actualQuantity == 0)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'tools', '/resources/tool/'.$fresh_newReference->id);
                            }
                        }
                        else
                        {
                            $newReference = Tool::find($input['reference']);
                            if($newReference->actualQuantity <=0)
                            {
                                return redirect(route('movements.create', 'tools'))->with('error', 'Tools record has been depleted and we cannot proceed with the item movement!');
                            }
                            //revert the changes from the old transaction
                            $revertQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            DB::table('tools')
                                ->where('id', $oldTransaction->id)
                                ->update(['actualQuantity' => $revertQuantity, 'totalPrice' => $revertPrice]);
                            //new transaction

                            $outgoingQuantity = $newReference->actualQuantity - $input['quantity'];
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                if($difference != 0)
                                {
                                    $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Tools Record has been depleted!';
                                }
                                else
                                {
                                    $response = 'Item Movement has been updated!';
                                }
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $outgoingQuantity) + $newReference->totalPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('tools')
                                ->where('id', $input['reference'])
                                ->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //apply new changes to item movement record
                            DB::table('movements')->where('id', $id)->update($input);
                            $fresh_newReference = $newReference->fresh();
                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'critical']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'tools', '/resources/tool/'.$fresh_newReference->id);
                            }
                            if($fresh_newReference->actualQuantity == 0)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'tools', '/resources/tool/'.$fresh_newReference->id);
                            }
                        }
                    }
                    else
                    {
                        if($movement->type != 'outgoing')
                        {
                            //revert the changes
                            $revertedQuantity = $oldTransaction->actualQuantity - $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice - $movement->totalPrice;
                            //new quantity and price
                            $outgoingQuantity =  $revertedQuantity - $input['quantity'];
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                if($difference != 0)
                                {
                                    $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Tools Record has been depleted!';
                                }
                                else
                                {
                                    $response = 'Item Movement has been updated!';
                                }
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $input['quantity']) - $revertedPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('tools')->where('id', $input['reference'])->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //update item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $outgoingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'tools',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $outgoingTotalPrice
                                ]);
                            $fresh_newReference = Tool::find($input['reference']);
                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'critical']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'tools', '/resources/tool/'.$fresh_newReference->id);
                            }
                            if($fresh_newReference->actualQuantity == 0)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'tools', '/resources/tool/'.$fresh_newReference->id);
                            }
                        }
                        else
                        {
                            //revert the changes
                            $revertedQuantity = $oldTransaction->actualQuantity + $movement->quantity;
                            $revertedPrice = $oldTransaction->totalPrice + $movement->totalPrice;
                            //new quantity and price
                            $outgoingQuantity = $revertedQuantity - $input['quantity'] ;
                            if($outgoingQuantity <= 0)
                            {
                                //get the difference
                                $difference = abs($outgoingQuantity);
                                //update the quantity that can only be accomodated
                                $input['quantity'] = $input['quantity'] - $difference;
                                //to zero since negative should be also zero
                                $outgoingQuantity = 0;
                                $response = 'Item Movement Updated but we can no longer accommodate '.$difference.' more since Tools Record has been depleted!';
                            }
                            else
                            {
                                $response = 'Item Movement has been updated!';
                            }
                            $outgoingTotalPrice = ($input['unitPrice'] * $input['quantity']) - $revertedPrice;
                            if($outgoingTotalPrice <= 0)
                            {
                                $outgoingTotalPrice = 0;
                            }
                            DB::table('tools')->where('id', $input['reference'])->update(['actualQuantity' => $outgoingQuantity, 'totalPrice' => $outgoingTotalPrice]);
                            //update item movement record
                            $input['month'] = date('F', strtotime($input['dateReceived']));
                            $input['year'] = date('Y', strtotime($input['dateReceived']));;
                            DB::table('movements')->where('id', $id)->update($input);
                            //update tracker
                            DB::table('movementstracker')
                                ->where('movementRef', $id)
                                ->update([
                                    'previousQuantity' => $revertedQuantity,
                                    'updateQuantity' => $outgoingQuantity,
                                    'entryBy' => $_SESSION['user'],
                                    'originGroup' => 'tools',
                                    'movementType' => $input['type'],
                                    'prevTotalPrice' => $revertedPrice,
                                    'updateTotalPrice' => $outgoingTotalPrice
                                ]);
                            $fresh_newReference = Tool::find($input['reference']);
                            if($fresh_newReference->actualQuantity <= $fresh_newReference->minimumQuantity)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'critical']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'critical', $_SESSION['user'], 'tools', '/resources/tool/'.$fresh_newReference->id);
                            }
                            if($fresh_newReference->actualQuantity == 0)
                            {
                                $fresh_newReference->update(['criticalTagging' => 'depleted']);
                                $this->notification->create($fresh_newReference->id, $fresh_newReference->partNumber, 'depleted', $_SESSION['user'], 'tools', '/resources/tool/'.$fresh_newReference->id);
                            }
                        }
                    }
                    break;
            }
            DB::commit();
            return redirect(route('movements.show', ['tools', $id]))->with('response', $response);
        }
        catch (\Exception $exception)
        {
            //rollback all db request
            DB::rollBack();
            //create a db error tracker
            $this->util->createError([
                $_SESSION['user'],
                'MOVEMENTEQUPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('movements.show', ['tools', $id]))->with('error', 'Oops something went wrong! Please contact your administrator.');
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
}
