<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeOfService;
class TSController extends Controller
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }
    //tested
    public function index()
    {
        $ts = TypeOfService::orderBy('typeOfService', 'ASC')->get();
        if(empty($typeOfServices))
        {
            $typeOfServices = $this->util->wrapMessage('error', 'No Record(s) Found!');
        }

        return view('references.typeofservice.index', ['data' => $ts, 'sidebar_selected' => 'References', 'reference_nav_selected' => 'ts']);
    }
    //tested
    public function show($id)
    {
        $ts = TypeOfService::find($id);
        if(empty($ts))
        {
            return redirect(route('typeOfServices'))->with('response', $this->util->wrapMessage('error', 'No Record(s) Found!'));
        }
        return view('reference.ts.show', ['data' => $ts]);
    }
    public function create()
    {
        return view('reference.ts.create');
    }
    //tested
    public function store(Request $request)
    {
        //check for duplicate
        $duplicate = TypeOfService::where('typeOfService', $request->input('typeOfService'))->first();

        if(! empty($duplicate))
        {
            return redirect(route('typeOfService.create'))->with('response', $this->util->wrapMessage('warning', 'Service Type has been added previously!'));
        }
        try
        {
            $typeOfService = new TypeOfService();
            $typeOfService->typeOfService = $request->input('typeOfService');
            $typeOfService->description = $request->input('description');
            $typeOfService->save();
            return redirect('/reference/typeOfService/show/'. $typeOfService->id)->with('response', $this->util->wrapMessage('success', 'You have created new Service Type!'));
        }
        catch(\Exception $exception)
        {
            $this->util->createError([
                @$_SESSION['user'],
                'TSSTORE',
                $exception->getMessage()
            ]);
            return redirect(route('typeOfServices'))->with('response', $this->util->wrapMessage('error', 'Oops something went wrong! Please contact your administrator.'));
        }
    }
    public function update($id, Request $request)
    {
        $typeOfService = TypeOfService::find($id);
        if(empty($typeOfService))
        {
            return redirect(route('typeOfServices'))->with('response', $this->util->wrapMessage('error', 'No Record(s) Found!'));
        }
        try
        {
            $typeOfService->update($request->post());
            return redirect('/reference/typeOfService/'. $typeOfService->id)->with('response', $this->util->wrapMessage('success', 'You have updated this Service Type!'));
        }
        catch (\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'TSUPDATE',
                $exception->getMessage()
            ]);
            return redirect('/reference/typeOfService/show/'. $typeOfService->id)->with('response', $this->util->wrapMessage('error', 'Oops something went wrong! Please contact your administrator.'));
        }
    }
    public function updateShow($id)
    {
        $typeOfService = TypeOfService::find($id);
        if(empty($typeOfService))
        {
            return redirect(route('typeOfService'))->with('response', $this->util->wrapMessage('error', 'No Record(s) Found!!'));
        }
        return view('reference.ts.update', ['data' => $typeOfService]);
    }
    public function destroy($id)
    {
        $typeOfService = TypeOfService::find($id);
        if(empty($typeOfService))
        {
            return redirect(route('typeOfServices'))->with('response', $this->util->wrapMessage('error', 'No Record(s) Found!'));
        }
        $typeOfService->delete();
        return redirect(route('typeOfServices'))->with('response', $this->util->wrapMessage('success', 'Service Type has been deleted!'));
    }
}
