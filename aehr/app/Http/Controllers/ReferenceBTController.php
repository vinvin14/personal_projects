<?php

namespace App\Http\Controllers;

use App\Models\Boardtype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferenceBTController extends Controller
{
    private $request, $util;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->util = new UtilitiesController();
    }
    public function index()
    {
        $board_types = Boardtype::query()->orderBy('boardType', 'ASC')->paginate(10);
        return view('references.boardtype.index', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'boardtypes'])
            ->with(compact('board_types'));
    }
    public function update($id)
    {
        $board_type = Boardtype::query()->findOrFail($id);
        return view('references.boardtype.update', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'boardtypes'])
            ->with(compact('board_type'));
    }
    public function upsave($id)
    {
        $board_type = Boardtype::query()->findOrFail($id);
        if($board_type->boardType != $this->request->input('boardType')) {
            if(! empty(Boardtype::query()->where('boardType', $this->request->input('boardType'))->first())) {
                return redirect(route('reference.boardtype.update', $id))
                    ->with('error', "$board_type->boardType has been added previously!");
            }
        }
        try {
            $board_type->update($this->request->post());
            return redirect(route('reference.boardtypes'))
                ->with('response', "$board_type->boardType has been updated!");
        } catch (\Exception $exception) {
            $this->util->createError([
                $_SESSION['user'],
                'REFERENCE_BT_UPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('reference.boardtype.update', $id))
                ->with('error', 'Something went wrong Please contact your Administrator!');
        }
    }
    public function create()
    {
        $existing_bt = Boardtype::query()->orderBy('boardType', 'ASC')->paginate(10);
        return view('references.boardtype.create', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'boardtypes'])
            ->with(compact('existing_bt'));
    }
    public function store()
    {
        $input = $this->request->except('_token');
        $duplicate = Boardtype::query()->where('boardType', $input['boardType'])->first();
        if(!empty($duplicate)) {
            return redirect(route('reference.boadtype.create'))->with('error', "$duplicate->boardType has been added previously!");
        }
        $faultcode = Boardtype::query()->create($input);
        return redirect(route('reference.boardtype.create'))->with('response', "$faultcode->boardType has been added to our records!");
    }
    public function destroy($id)
    {
        $system_type = Boardtype::query()->findOrFail($id);
        $system_type->delete();
        return redirect(route('reference.boardtypes'))->with('response', 'Board Type successfully deleted!');
    }
    public function search(Request $request)
    {
        $results = DB::table('boardtypes')
                        ->where('boardType', 'LIKE', '%'.$request->get('keyword').'%')
                        ->orWhere('description', 'LIKE', '%'.$request->get('keyword').'%')
                        ->paginate(10);
        return view('references.boardtype.search_results', ['board_types' => $results, 'total_results' => count($results), 'sidebar_selected' => 'References', 'reference_nav_selected' => 'boardtypes']);

    }
}
