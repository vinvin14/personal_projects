<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovementTrackerController extends Controller
{
    public function create($movement, $item, $previous_quantity, $update_quantity, $record_date, $entry_by, $origin_group, $movement_type, $prev_price, $update_price, $transaction)
    {
        DB::table('movementTracker')
            ->insert([
                'movementRef' => $movement,
                'itemRef' => $item,
                'previousQuantity' => $previous_quantity,
                'updateQuantity' => $update_quantity,
                'recordDate' => $record_date,
                'entryBy' => $entry_by,
                'originGroup' => $origin_group,
                'movementType' => $movement_type,
                'prevTotalPrice' => $prev_price,
                'updateTotalPrice' => $update_price,
                'transaction' => $transaction
            ]);
    }
}
