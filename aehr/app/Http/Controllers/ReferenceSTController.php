<?php

namespace App\Http\Controllers;

use App\Models\SystemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferenceSTController extends Controller
{
    private $request, $util;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->util = new UtilitiesController();
    }
    public function index()
    {
        $system_types = SystemType::query()->orderBy('systemType', 'ASC')->paginate(10);
        return view('references.systemtype.index', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'systemtypes'])
            ->with(compact('system_types'));
    }
    public function update($id)
    {
        $system_type =SystemType::query()->findOrFail($id);
        return view('references.systemtype.update', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'systemtypes'])
            ->with(compact('system_type'));
    }
    public function upsave($id)
    {
        $system_type = SystemType::query()->findOrFail($id);
        if($system_type->systemType != $this->request->input('systemType')) {
            if(! empty(SystemType::query()->where('systemType', $this->request->input('systemType'))->first())) {
                return redirect(route('reference.systemtype.update', $id))
                    ->with('error', "$system_type->systemType has been added previously!");
            }
        }
        try {
            $system_type->update($this->request->post());
            return redirect(route('reference.systemtypes'))
                ->with('response', "$system_type->systemType has been updated!");
        } catch (\Exception $exception) {
            $this->util->createError([
                $_SESSION['user'],
                'REFERENCE_ST_UPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('reference.systemtype.update', $id))
                ->with('error', 'Something went wrong Please contact your Administrator!');
        }

    }
    public function create()
    {
        $existing_st = SystemType::query()->orderBy('systemType', 'ASC')->paginate(10);
        return view('references.systemtype.create', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'systemtypes'])
            ->with(compact('existing_st'));
    }
    public function store()
    {
        $input = $this->request->except('_token');
        $duplicate = SystemType::query()->where('systemType', $input['systemType'])->first();
        if(!empty($duplicate)) {
            return redirect(route('reference.systemtype.create'))->with('error', "$duplicate->systemType has been added previously!");
        }
        $faultcode = SystemType::query()->create($input);
        return redirect(route('reference.systemtype.create'))->with('response', "$faultcode->systemType has been added to our records!");
    }
    public function destroy($id)
    {
        $system_type = SystemType::query()->findOrFail($id);
        $system_type->delete();
        return redirect(route('reference.systemtypes'))->with('response', 'System Type successfully deleted!');
    }
    public function search(Request $request)
    {
        $results = DB::table('systemtypes')
                        ->where('systemType', 'LIKE', '%'.$request->get('keyword').'%')
                        ->orWhere('description', 'LIKE', '%'.$request->get('keyword').'%')
                        ->paginate(10);
                    return view('references.systemtype.search_results', ['system_types' => $results, 'total_results' => count($results), 'sidebar_selected' => 'References', 'reference_nav_selected' => 'systemtypes']);

    }
}
