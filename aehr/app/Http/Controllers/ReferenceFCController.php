<?php

namespace App\Http\Controllers;

use App\Models\FaultCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferenceFCController extends Controller
{
    private $request, $util;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->util = new UtilitiesController();
    }
    public function index()
    {
        $faultcodes = FaultCode::query()->orderBy('code', 'ASC')->paginate(10);
        return view('references.faultcode.index', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'faultcodes'])
                ->with(compact('faultcodes'));
    }
    public function update($id)
    {
        $faultcode =FaultCode::query()->findOrFail($id);
        return view('references.faultcode.update', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'faultcodes'])
                ->with(compact('faultcode'));
    }
    public function upsave($id)
    {
        $faultcode = FaultCode::query()->findOrFail($id);
        if($faultcode->code != $this->request->input('code')) {
            if(! empty(FaultCode::query()->where('code', $this->request->input('code')->first()))) {
                return redirect(route('reference.faultcode.update', $id))
                        ->with('error', "$faultcode->code has been added previously!");
            }
        }
        try {
            $faultcode->update($this->request->post());
            return redirect(route('reference.faultcodes'))
                    ->with('response', "$faultcode->code has been updated!");
        } catch (\Exception $exception) {
            $this->util->createError([
                $_SESSION['user'],
                'REFERENCE_FC_UPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('reference.faultcode.update', $id))
                    ->with('error', 'Something went wrong Please contact your Administrator!');
        }

    }
    public function create()
    {
        $existing_fc = FaultCode::query()->orderBy('code', 'ASC')->paginate(10);
        return view('references.faultcode.create', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'faultcodes'])
                ->with(compact('existing_fc'));
    }
    public function store()
    {
        $input = $this->request->except('_token');
        $duplicate = FaultCode::query()->where('code', $input['code'])->first();
        if(!empty($duplicate)) {
            return redirect(route('reference.faultcode.create'))->with('error', "$duplicate->code has been added previously!");
        }
        $faultcode = FaultCode::query()->create($input);
        return redirect(route('reference.faultcode.create'))->with('response', "$faultcode->code has been added to our records!");
    }
    public function destroy($id)
    {
        $faultcode = FaultCode::query()->findOrFail($id);
        $faultcode->delete();
        return redirect(route('reference.faultcodes'))->with('response', 'Fault Code successfully deleted!');
    }
    public function search(Request $request)
    {
        $results = DB::table('faultcodes')
                        ->where('code', 'LIKE', '%'.$request->get('keyword').'%')
                        ->orWhere('type', 'LIKE', '%'.$request->get('keyword').'%')
                        ->orWhere('description', 'LIKE', '%'.$request->get('keyword').'%')
                        ->paginate(10);
        return view('references.faultcode.search_results', ['faultcodes' => $results, 'total_results' => count($results), 'sidebar_selected' => 'References', 'reference_nav_selected' => 'faultcodes']);

    }
}
