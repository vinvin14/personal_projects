<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 26/11/2020
 * Time: 9:54 PM
 */

namespace App\Http\Controllers\api;
use App\Http\Controllers\UtilitiesController;
use App\Models\FSE;
use Illuminate\Http\Request;

class FEController
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }

    public function index()
    {
        $fes = FSE::orderBy('lastname', 'ASC')->get();
        if(empty($fes))
        {
            return response()->json('No Record(s) Found!', 404);
        }
        return response()->json($fes, 200);
    }
    public function show($id)
    {
        $fc = FSE::find($id);
        if(empty($fc))
        {
            return response()->json('Record not found!', 404);
        }
        return response()->json($fc, 200);
    }
    public function update($id, Request $request)
    {
        $fc = FSE::find($id);
        $util = new UtilitiesController();
        $duplicate = FSE::where([
            'firstname' => $request->input('firstname'),
            'middlename' => $request->input('middlename'),
            'lastname' => $request->input('lastname'),
            'position' => $request->input('position'),
        ])->first();

        if(! empty($duplicate))
        {
            if($fc->firstname != $request->input('firstname') && $fc->middlename != $request->input('middlename') && $fc->lastname != $request->input('lastname'))
            {
                return response()->json('Entry that you want to push through will cause duplication!!', 404);
            }
        }
        if(empty($fc))
        {
            return response()->json('FSE Record not found!', 404);
        }
        try
        {
            $fc->update($request->post());
            return response()->json('FSE Record Successfully Updated!', 200);
        }
        catch (\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'FEUPDATE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function store(Request $request)
    {
        $input = $request->post();
        $util = new UtilitiesController();
        $duplicate = FSE::where([
            'firstname' => $request->input('firstname'),
            'middlename' => $request->input('middlename'),
            'lastname' => $request->input('lastname'),
            'position' => $request->input('position'),
        ])->first();
        if(! empty($duplicate))
        {
            return response()->json('FSE Record has been added previously!', 404);
        }
        try
        {
            FSE::create($input);
            return response()->json('You have added new FSE!', 200);
        }
        catch(\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'FESTORE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function destroy($id)
    {
        $unit = FSE::find($id);
        if(empty($unit))
        {
            return response()->json('Record not found!', 404);
        }
        $unit->delete();
        return response()->json('Successfully deleted!', 200);
    }
}
