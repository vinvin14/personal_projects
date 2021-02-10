<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferenceLocationController extends Controller
{
    private $request, $util;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->util = new UtilitiesController();
    }
    public function index()
    {
        $locations = Location::query()->orderBy('location', 'ASC')->paginate(10);
        return view('references.location.index', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'location'])
            ->with(compact('locations'));
    }
    public function update($id)
    {
        $location = Location::query()->findOrFail($id);
        return view('references.location.update', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'location'])
            ->with(compact('location'));
    }
    public function upsave($id)
    {
        $location = Location::query()->findOrFail($id);
        if($location->location != $this->request->input('location')) {
            if(! empty(Location::query()->where('location', $this->request->input('location'))->first())) {
                return redirect(route('reference.location.update', $id))
                    ->with('error', "$location->location has been added previously!");
            }
        }
        try {
            $location->update($this->request->post());
            return redirect(route('reference.locations'))
                ->with('response', "$location->location has been updated!");
        } catch (\Exception $exception) {
            $this->util->createError([
                $_SESSION['user'],
                'REFERENCE_LOCATION',
                $exception->getMessage()
            ]);
            return redirect(route('reference.location.update', $id))
                ->with('error', 'Something went wrong Please contact your Administrator!');
        }

    }
    public function create()
    {
        $existing_location = Location::query()->orderBy('location', 'ASC')->paginate(10);
        return view('references.location.create', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'location'])
            ->with(compact('existing_location'));
    }
    public function store()
    {
        $input = $this->request->except('_token');
        $duplicate = Location::query()->where('location', $input['location'])->first();
        if(!empty($duplicate)) {
            return redirect(route('reference.location.create'))->with('error', "$duplicate->location has been added previously!");
        }
        $location = Location::query()->create($input);
        return redirect(route('reference.location.create'))->with('response', "$location->location has been added to our records!");
    }
    public function destroy($id)
    {
        $location = Location::query()->findOrFail($id);
        $location->delete();
        return redirect(route('reference.locations'))->with('response', 'Location successfully deleted!');
    }
    public function search(Request $request)
    {
        $results = DB::table('locations')
            ->where('location', 'LIKE', '%'.$request->get('keyword').'%')
            ->orWhere('description', 'LIKE', '%'.$request->get('keyword').'%')
            ->paginate(10);
        return view('references.location.search_results', ['locations' => $results, 'total_results' => count($results), 'sidebar_selected' => 'References', 'reference_nav_selected' => 'location']);

    }
}
