<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferenceUnitController extends Controller
{
    private $request, $util;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->util = new UtilitiesController();
    }

    public function index()
    {
        $units = Unit::query()->orderBy('unit', 'ASC')->paginate(10);
        return view('references.unit.index', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'units'])->with(compact('units'));
    }
    public function show($id)
    {
        $unit = Unit::query()->findOrFail($id);
        return view('references.unit.show')->with(compact('unit'));
    }
    public function update($id)
    {
        $unit = Unit::query()->findOrFail($id);
        return view('references.unit.update')->with(compact('unit'));
    }
    public function upsave($id)
    {
        $curr_unit = Unit::query()->findOrFail($id);
        if($curr_unit->unit != $this->request->input('unit')) {
            $duplicate = Unit::query()->where('unit', $this->request->input('unit'))->first();
        }
        if(! empty($duplicate)) {
            return redirect(route('reference.units'))->with('error', 'Request Denied! Unit has been added before and will cause duplication!');
        }

        try {
            $curr_unit->update($this->request->post());
            return redirect(route('reference.units'))->with('response', "$curr_unit->unit has been updated!");
        } catch (\Exception $exception) {
            $this->util->createError([
                $_SESSION['user'],
                'REFERENCE_UNIT_UPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('reference.unit.update', $id))->with('error', 'Something went wrong Please contact your Administrator!');
        }
    }
    public function create()
    {
        $existing_units = Unit::query()->orderBy('unit', 'ASC')->paginate(10);
        return view('references.unit.create', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'units'])->with(compact('existing_units'));
    }
    public function store()
    {
        $input = $this->request->except('_token');
        $duplicate = Unit::where('unit', $input['unit'])->first();

        if(! empty($duplicate)) {
            return redirect(route('reference.unit.create'))->with('error', "$duplicate->unit has been added previously!");
        }
        Unit::query()->create($input);
        return redirect(route('reference.unit.create'))->with('response', 'Unit has been successfully added!');
    }
    public function destroy($id)
    {
        $unit = Unit::query()->findOrFail($id);
        $unit->delete();
        return redirect(route('reference.units'))->with('response', 'Unit Record has been deleted!');
    }
    public function search(Request $request)
    {
        $results = DB::table('units')
                        ->where('unit', 'LIKE', '%'.$request->get('keyword').'%')
                        ->orWhere('description', 'LIKE', '%'.$request->get('keyword').'%')
                        ->paginate(10);
        return view('references.unit.search_results', ['units' => $results, 'total_results' => count($results), 'sidebar_selected' => 'References', 'reference_nav_selected' => 'units']);

    }
}
