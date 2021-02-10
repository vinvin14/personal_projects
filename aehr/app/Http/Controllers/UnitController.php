<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 15/11/2020
 * Time: 8:57 PM
 */

namespace App\Http\Controllers;


use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }
    //tested
    public function index()
    {
        $units = Unit::orderBy('unit', 'ASC')->get();
        if(empty($units))
        {
            $units = $this->util->wrapMessage('error', 'No Record(s) Found!');
        }
        return view('references.unit.index', ['data' => $units, 'sidebar_selected' => 'References', 'reference_nav_selected' => 'units']);
    }
    //tested
    public function show($id)
    {
        $unit = Unit::find($id);
        if(empty($unit))
        {
            return redirect(route('units'))->with('response', $this->util->wrapMessage('error', 'Unit not found!'));
        }
        return view('reference.unit.show', ['data' => $unit]);
    }
    public function create()
    {
        return view('reference.unit.create');
    }
    //tested
    public function store(Request $request)
    {
        //check for duplicate
        $duplicate = Unit::where('unit', $request->input('unit'))->first();

        if(! empty($duplicate))
        {
            return redirect(route('unit.create'))->with('response', $this->util->wrapMessage('warning', 'Unit has been added previously!'));
        }
        try
        {
            $unit = new Unit();
            $unit->unit = $request->input('unit');
            $unit->description = $request->input('description');
            $unit->save();
            dd($unit);
            return redirect('/reference/unit/show/'. $unit->id)->with('response', $this->util->wrapMessage('success', 'You have created new unit!'));
        }
        catch(\Exception $exception)
        {
            $this->util->createError([
                @$_SESSION['user'],
                'UNITSTORE',
                $exception->getMessage()
            ]);
            return redirect(route('units'))->with('response', $this->util->wrapMessage('error', 'Oops something went wrong! Please contact your administrator.'));
        }
    }
    public function update($id, Request $request)
    {
        $unit = Unit::find($id);
        if(empty($unit))
        {
            return redirect(route('unit'))->with('response', $this->util->wrapMessage('error', 'Unit not found!'));
        }
        try
        {
            $unit->update($request->post());
            return redirect('/reference/unit/'. $unit->id)->with('response', $this->util->wrapMessage('success', 'You have updated this unit!'));
        }
        catch (\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'UNITUPDATE',
                $exception->getMessage()
            ]);
            return redirect('/reference/unit/show/'. $unit->id)->with('response', $this->util->wrapMessage('error', 'Oops something went wrong! Please contact your administrator.'));
        }
    }
    public function updateShow($id)
    {
        $unit = Unit::find($id);
        if(empty($unit))
        {
            return redirect(route('unit'))->with('response', $this->util->wrapMessage('error', 'Unit not found!'));
        }
        return view('reference.unit.update', ['data' => $unit]);
    }
    public function destroy($id)
    {
        $unit = Unit::find($id);
        if(empty($unit))
        {
            return redirect(route('units'))->with('response', $this->util->wrapMessage('error', 'Unit not found!'));
        }
        $unit->delete();
        return redirect(route('units'))->with('response', $this->util->wrapMessage('success', 'Unit has been deleted!'));
    }
}
