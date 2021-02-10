<?php

namespace App\Http\Controllers;

use App\Models\Purpose;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferencePurposeController extends Controller
{
    private $request, $util;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->util = new UtilitiesController();
    }
    public function index()
    {
//        $purposes = Purpose::query()->orderBy('purpose', 'ASC')->paginate(10);
        $purposes = DB::table('purposes')
                        ->where('purpose', '!=', 'RMA')
                        ->paginate(10);
        return view('references.purpose.index', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'purpose'])
            ->with(compact('purposes'));
    }
    public function update($id)
    {
        $purpose = Purpose::query()->findOrFail($id);
        return view('references.purpose.update', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'purpose'])
            ->with(compact('purpose'));
    }
    public function upsave($id)
    {
        $purpose = Purpose::query()->findOrFail($id);
        if($purpose->purpose != $this->request->input('purpose')) {
            if(! empty(Purpose::query()->where('purpose', $this->request->input('purpose'))->first())) {
                return redirect(route('reference.purpose.update', $id))
                    ->with('error', "$purpose->purpose has been added previously!");
            }
        }
        try {
            $purpose->update($this->request->post());
            return redirect(route('reference.purposes'))
                ->with('response', "$purpose->purpose has been updated!");
        } catch (\Exception $exception) {
            $this->util->createError([
                $_SESSION['user'],
                'REFERENCE_PURPOSE',
                $exception->getMessage()
            ]);
            return redirect(route('reference.location.update', $id))
                ->with('error', 'Something went wrong Please contact your Administrator!');
        }

    }
    public function create()
    {
        $existing_purpose = Purpose::query()->orderBy('purpose', 'ASC')->paginate(10);
        return view('references.purpose.create', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'purpose'])
            ->with(compact('existing_purpose'));
    }
    public function store()
    {
        $input = $this->request->except('_token');
        $duplicate = Purpose::query()->where('purpose', $input['purpose'])->first();
        if(!empty($duplicate)) {
            return redirect(route('reference.purpose.create'))->with('error', "$duplicate->purpose has been added previously!");
        }
        $purpose = Purpose::query()->create($input);
        return redirect(route('reference.purpose.create'))->with('response', "$purpose->purpose has been added to our records!");
    }
    public function destroy($id)
    {
        $purpose = Purpose::query()->findOrFail($id);
        $purpose->delete();
        return redirect(route('reference.purposes'))->with('response', 'Purpose successfully deleted!');
    }
    public function search(Request $request)
    {
        $results = DB::table('purposes')
                        ->where('purpose', 'LIKE', '%'.$request->get('keyword').'%')
                        ->orWhere('description', 'LIKE', '%'.$request->get('keyword').'%')
                        ->paginate(10);
        return view('references.purpose.search_results', ['purposes' => $results, 'total_results' => count($results), 'sidebar_selected' => 'References', 'reference_nav_selected' => 'purpose']);

    }
}
