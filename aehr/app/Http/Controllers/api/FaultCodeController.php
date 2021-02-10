<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19/11/2020
 * Time: 6:31 PM
 */

namespace App\Http\Controllers\api;


use App\Models\FaultCode;
use Illuminate\Http\Request;
use App\Http\Controllers\UtilitiesController;

class FaultCodeController
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }

    public function index()
    {
        $faultCodes = FaultCode::orderBy('code', 'ASC')->get();
        if(empty($faultCodes))
        {
            return response()->json('No Record(s) Found!', 404);
        }
        return response()->json($faultCodes, 200);
    }
    public function show($id)
    {
        $fc = FaultCode::find($id);
        if(empty($fc))
        {
            return response()->json('Fault Code not found!', 404);
        }
        return response()->json($fc, 200);
    }
    public function update($id, Request $request)
    {
        $fc = FaultCode::find($id);
        $util = new UtilitiesController();
        $duplicate = FaultCode::where([
            'code' => $request->input('code'),
            'type' => $request->input('type'),
        ])->first();

        if(! empty($duplicate))
        {
            if($fc->code != $request->input('code'))
            {
                return response()->json('Entry that you want to push through will cause duplication!!', 404);
            }
        }
        if(empty($fc))
        {
            return response()->json('Fault Code not found!', 404);
        }
        try
        {
            $fc->update($request->post());
            return response()->json('Fault Code Successfully Updated!', 200);
        }
        catch (\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'FCUPDATE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function store(Request $request)
    {
        $input = $request->post();
        $util = new UtilitiesController();
        $duplicate = FaultCode::where([
            'code' => $input['code'],
            'type' => $input['type'],
        ])->first();
        if(empty($input['description']))
        {
            $input['description'] = 'No Data';
        }
        if(! empty($duplicate))
        {
            return response()->json('Fault Code has been added previously!', 404);
        }
        try
        {
            $input['dateAdded'] = date('m-d-y');
            FaultCode::create($input);
            return response()->json('You have added new Fault Code!', 200);
        }
        catch(\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'FCSTORE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function destroy($id)
    {
        $unit = FaultCode::find($id);
        if(empty($unit))
        {
            return response()->json('Fault Code not found!', 404);
        }
        $unit->delete();
        return response()->json('Successfully deleted!', 200);
    }

}
