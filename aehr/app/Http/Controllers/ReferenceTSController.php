<?php

namespace App\Http\Controllers;

use App\Models\TypeOfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferenceTSController extends Controller
{
    private $request, $util;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->util = new UtilitiesController();
    }
    public function index()
    {
        $type_of_services = TypeOfService::query()->orderBy('typeOfService', 'ASC')->paginate(10);
        return view('references.typeofservice.index', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'typeofservices'])
            ->with(compact('type_of_services'));
    }
    public function update($id)
    {
        $type_of_service = TypeOfService::query()->findOrFail($id);
        return view('references.typeofservice.update', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'typeofservices'])
            ->with(compact('type_of_service'));
    }
    public function upsave($id)
    {
        $type_of_service = TypeOfService::query()->findOrFail($id);
        if($type_of_service->typeOfService != $this->request->input('typeOfService')) {
            if(! empty(TypeOfService::query()->where('typeOfService', $this->request->input('typeOfService'))->first())) {
                return redirect(route('reference.typeofservice.update', $id))
                    ->with('error', "$type_of_service->typeOfService has been added previously!");
            }
        }
        try {
            $type_of_service->update($this->request->post());
            return redirect(route('reference.typeofservices'))
                ->with('response', "$type_of_service->typeOfService has been updated!");
        } catch (\Exception $exception) {
            $this->util->createError([
                $_SESSION['user'],
                'REFERENCE_TS_UPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('reference.typeofservice.update', $id))
                ->with('error', 'Something went wrong Please contact your Administrator!');
        }
    }
    public function create()
    {
        $existing_ts = TypeOfService::query()->orderBy('typeOfService', 'ASC')->paginate(10);
        return view('references.typeofservice.create', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'typeofservices'])
            ->with(compact('existing_ts'));
    }
    public function store()
    {
        $input = $this->request->except('_token');
        $duplicate = TypeOfService::query()->where('typeOfService', $input['typeOfService'])->first();
        if(!empty($duplicate)) {
            return redirect(route('reference.typeofservice.create'))->with('error', "$duplicate->typeOfService has been added previously!");
        }
        $type_of_service = TypeOfService::query()->create($input);
        return redirect(route('reference.typeofservice.create'))->with('response', "$type_of_service->typeOfService has been added to our records!");
    }
    public function destroy($id)
    {
        $system_type = TypeOfService::query()->findOrFail($id);
        $system_type->delete();
        return redirect(route('reference.typeofservices'))->with('response', 'Type Service successfully deleted!');
    }
    public function search(Request $request)
    {
        $results = DB::table('typeofservices')
                        ->where('typeOfService', 'LIKE', '%'.$request->get('keyword').'%')
                        ->orWhere('description', 'LIKE', '%'.$request->get('keyword').'%')
                        ->paginate(10);
        return view('references.typeofservice.search_results', ['type_of_services' => $results, 'total_results' => count($results), 'sidebar_selected' => 'References', 'reference_nav_selected' => 'typeofservices']);
    }
}
