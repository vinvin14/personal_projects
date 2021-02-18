<?php
/**
 * Created by PhpStorm.
 * User: DOH-VIN
 * Date: 04/12/2020
 * Time: 8:16 AM
 */

namespace App\Http\Controllers;
use App\Exports\ComponentIncomingExport;
use App\Exports\ComponentOutgoingExport;
use App\Exports\ConsumableExport;
use App\Exports\ConsumableOutgoingExport;
use App\Exports\EquipmentExport;
use App\Exports\RepairExport;
use App\Exports\ConsignedIncomingExport;
use App\Exports\ConsignedOutgoingExport;
use App\Exports\ToolExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reports.index', ['sidebar_selected' => 'Reports']);
    }
    public function repairs()
    {
        return Excel::download(new RepairExport(), 'repair.xlsx');
    }
    public function generate($type, Request $request)
    {
        switch ($type)
        {
            case 'repairs':
                return Excel::download(new RepairExport($request->except('_token')), 'repairs.xlsx');
                break;
            case 'consumables':
//                return Excel::download(new ConsumableExport($request->except('_token')), 'consumables.xlsx');
                if($request->input('type') == 'Incoming')
                {
                    return Excel::download(new ConsumableExport($request->except('_token')), 'consumables_incoming.xlsx');
                }
                else
                {
                    return Excel::download(new ConsumableOutgoingExport($request->except('_token')), 'consumables_outgoing.xlsx');
                }
                break;
                break;
            case 'components':
                if($request->input('type') == 'Incoming')
                {
                    return Excel::download(new ComponentIncomingExport($request->except('_token')), 'components_incoming.xlsx');
                }
                else
                {
                    return Excel::download(new ComponentOutgoingExport($request->except('_token')), 'components_outgoing.xlsx');
                }
                break;
            case 'consigned':
                if($request->input('type') == 'Incoming')
                {
                    return Excel::download(new ConsignedIncomingExport($request->except('_token')), 'consigned_spares_incoming.xlsx');
                }
                else
                {
                    return Excel::download(new ConsignedOutgoingExport($request->except('_token')), 'consigned_spares_outgoing.xlsx');
                }
                break;
            case 'equipment':
                return Excel::download(new EquipmentExport($request->except('_token')), 'equipment.xlsx');
                break;
            case 'tools':
                return Excel::download(new ToolExport($request->except('_token')), 'tools.xlsx');
                break;

        }
    }
}
