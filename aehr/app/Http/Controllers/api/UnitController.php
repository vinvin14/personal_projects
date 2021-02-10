<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 15/11/2020
 * Time: 8:57 PM
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\UtilitiesController;
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
            return response()->json('No Record(s) Found!', 404);
        }
        return response()->json($units, 200);
    }
    //tested
    public function show($id)
    {
        $unit = Unit::find($id);
        if(empty($unit))
        {
            return response()->json('Unit not found!', 404);
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
        $input = $request->post();
        //check for duplicate
        $duplicate = Unit::where('unit', $input['unit'])->first();
        if(empty($input['description']))
        {
            $input['description'] = 'No Data';
        }
        if(! empty($duplicate))
        {
            return response()->json('Unit has been added previously!', 404);
        }
        try
        {
            Unit::create($input);
            return response()->json('You have created new unit!', 200);
        }
        catch(\Exception $exception)
        {
            $this->util->createError([
                @$_SESSION['user'],
                'UNITSTORE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function update($id, Request $request)
    {
        $unit = Unit::find($id);
        $duplicate = Unit::where([
            'unit' => $request->input('unit'),
        ])->first();

        if(! empty($duplicate))
        {
            if($unit->unit != $request->input('unit'))
            {
                return response()->json('Entry that you want to push through will cause duplication!!', 404);
            }
        }
        if(empty($unit))
        {
            return response()->json('Unit not found!', 404);
        }
        try
        {
            $unit->update($request->post());
            return response()->json('You have updated this unit!', 200);
        }
        catch (\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'UNITUPDATE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function destroy($id)
    {
        $unit = Unit::find($id);
        if(empty($unit))
        {
            return redirect(route('units'))->with('response', $this->util->wrapMessage('error', 'Unit not found!'));
            return response()->json('Unit not found!', 404);
        }
        $unit->delete();
        return response()->json('Successfully deleted!', 200);
    }
}
