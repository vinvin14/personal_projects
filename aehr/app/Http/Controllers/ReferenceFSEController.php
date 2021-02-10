<?php

namespace App\Http\Controllers;

use App\Models\FSE;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferenceFSEController extends Controller
{
    private $request, $util;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->util = new UtilitiesController();
    }
    public function index()
    {
        $fse = FSE::query()->orderBy('lastname', 'ASC')->paginate(10);
        return view('references.fse.index', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'fse'])
            ->with(compact('fse'));
    }
    public function update($id)
    {
        $fse = FSE::query()->findOrFail($id);
        return view('references.fse.update', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'fse'])
            ->with(compact('fse'));
    }
    public function upsave($id)
    {
        $fse = FSE::query()->findOrFail($id);
        if(
            $fse->lastname != $this->request->input('lastname')
            && $fse->firstname != $this->request->input('firstname')
            && $fse->middlename != $this->request->input('middlename')
        ) {
            if(! empty(
                FSE::query()
                    ->where(
                        [
                            'lastname' => $this->request->input('lastname'),
                            'firstname' => $this->request->input('firstname'),
                            'middlename' => $this->request->input('middlename')
                        ]
                    )->first())
            ) {
                return redirect(route('reference.fse.update', $id))
                    ->with('error', "$fse->lastname, $fse->firstname has been added previously!");
            }
        }
        try {
            $fse->update($this->request->post());
            return redirect(route('reference.fses'))
                ->with('response', "$fse->lastname, $fse->firstname information has been updated!");
        } catch (\Exception $exception) {
            $this->util->createError([
                $_SESSION['user'],
                'REFERENCE_FSE',
                $exception->getMessage()
            ]);
            return redirect(route('reference.fse.update', $id))
                ->with('error', 'Something went wrong Please contact your Administrator!');
        }

    }
    public function create()
    {
        $existing_fse = FSE::query()->orderBy('lastname', 'ASC')->paginate(10);
        return view('references.fse.create', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'fse'])
            ->with(compact('existing_fse'));
    }
    public function store()
    {
        $input = $this->request->except('_token');
        $duplicate = FSE::query()
                            ->where(
                                [
                                    'lastname' => $this->request->input('lastname'),
                                    'firstname' => $this->request->input('firstname'),
                                    'middlename' => $this->request->input('middlename')
                                ]
                            )->first();
        if(!empty($duplicate)) {
            return redirect(route('reference.fse.create'))->with('error', "$duplicate->lastname, $duplicate->firstname has been added previously!");
        }
        $fse = FSE::query()->create($input);
        return redirect(route('reference.fse.create'))->with('response', "$fse->lastname, $fse->firstname has been added to our records!");
    }
    public function destroy($id)
    {
        $fse = FSE::query()->findOrFail($id);
        $fse->delete();
        return redirect(route('reference.fse'))->with('response', 'Record successfully deleted!');
    }
    public function search(Request $request)
    {
        $results = DB::table('officer')
            ->where('firstname', 'LIKE', '%'.$request->get('keyword').'%')
            ->orWhere('middlename', 'LIKE', '%'.$request->get('keyword').'%')
            ->orWhere('lastname', 'LIKE', '%'.$request->get('keyword').'%')
            ->paginate(10);
        return view('references.fse.search_results', ['fse' => $results, 'total_results' => count($results), 'sidebar_selected' => 'References', 'reference_nav_selected' => 'customers']);

    }
}
