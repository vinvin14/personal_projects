<?php

namespace App\Http\Controllers;

use App\Models\SystemType;
use Illuminate\Http\Request;

class SystemTypeController extends Controller
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }
    //tested
    public function index()
    {
        $systemtypes = SystemType::orderBy('systemType', 'ASC')->get();
        if(empty($systemtypes))
        {
            $systemtypes = $this->util->wrapMessage('error', 'No Record(s) Found!');
        }
        return view('references.systemtype.index', ['data' => $systemtypes, 'sidebar_selected' => 'References', 'reference_nav_selected' => 'systemtype']);
    }
    //tested
    public function show($id)
    {
        $systemtype = systemtype::find($id);
        if(empty($systemtype))
        {
            return redirect(route('systemtypes'))->with('response', $this->util->wrapMessage('error', 'No Record(s) Found!'));
        }
        return view('reference.systemtype.show', ['data' => $systemtype]);
    }
    public function create()
    {
        return view('reference.systemtype.create');
    }
    //tested
    public function store(Request $request)
    {
        //check for duplicate
        $duplicate = systemtype::where('systemType', $request->input('systemType'))->first();

        if(! empty($duplicate))
        {
            return redirect(route('systemtype.create'))->with('response', $this->util->wrapMessage('warning', 'System Type has been added previously!'));
        }
        try
        {
            $systemtype = new systemtype();
            $systemtype->systemtype = $request->input('systemType');
            $systemtype->description = $request->input('description');
            $systemtype->save();
            dd($systemtype);
            return redirect('/reference/systemtype/show/'. $systemtype->id)->with('response', $this->util->wrapMessage('success', 'You have created new System Type!'));
        }
        catch(\Exception $exception)
        {
            $this->util->createError([
                @$_SESSION['user'],
                'STSTORE',
                $exception->getMessage()
            ]);
            return redirect(route('systemtypes'))->with('response', $this->util->wrapMessage('error', 'Oops something went wrong! Please contact your administrator.'));
        }
    }
    public function update($id, Request $request)
    {
        $systemtype = SystemType::find($id);
        if(empty($systemtype))
        {
            return redirect(route('systemtype'))->with('response', $this->util->wrapMessage('error', 'systemtype not found!'));
        }
        try
        {
            $systemtype->update($request->post());
            return redirect('/reference/systemtype/'. $systemtype->id)->with('response', $this->util->wrapMessage('success', 'You have updated this systemtype!'));
        }
        catch (\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'STUPDATE',
                $exception->getMessage()
            ]);
            return redirect('/reference/systemtype/show/'. $systemtype->id)->with('response', $this->util->wrapMessage('error', 'Oops something went wrong! Please contact your administrator.'));
        }
    }
    public function updateShow($id)
    {
        $systemtype = SystemType::find($id);
        if(empty($systemtype))
        {
            return redirect(route('systemtypes'))->with('response', $this->util->wrapMessage('error', 'No Record(s) Found!'));
        }
        return view('reference.systemtype.update', ['data' => $systemtype]);
    }
    public function destroy($id)
    {
        $systemtype = SystemType::find($id);
        if(empty($systemtype))
        {
            return redirect(route('systemtypes'))->with('response', $this->util->wrapMessage('error', 'No Record(s) Found!'));
        }
        $systemtype->delete();
        return redirect(route('systemtypes'))->with('response', $this->util->wrapMessage('success', 'System Type has been deleted!'));
    }
}
