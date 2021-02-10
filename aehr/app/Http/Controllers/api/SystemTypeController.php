<?php
/**
 * Created by PhpStorm.
 * User: DOH-VIN
 * Date: 26/11/2020
 * Time: 1:04 PM
 */

namespace App\Http\Controllers\api;

use App\Http\Controllers\UtilitiesController;
use App\Models\SystemType;
use Illuminate\Http\Request;

class SystemTypeController
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }

    public function index()
    {
        $SystemTypes = SystemType::orderBy('systemType', 'ASC')->get();
        if(empty($SystemTypes->toArray()))
        {
            return response()->json('No Record(s) Found!', 404);
        }
        return response()->json($SystemTypes, 200);
    }
    public function show($id)
    {
        $systemType = SystemType::find($id);
        if(empty($systemType))
        {
            return response()->json('System Type not found!', 404);
        }
        return response()->json($systemType, 200);
    }
    public function update($id, Request $request)
    {
        $systemType = SystemType::find($id);
        $util = new UtilitiesController();
        $duplicate = SystemType::where([
            'systemType' => $request->input('systemType'),
        ])->first();

        if(! empty($duplicate))
        {
            if($systemType->systemType != $request->input('systemType'))
            {
                return response()->json('Entry that you want to push through will cause duplication!!', 404);
            }
        }
        if(empty($systemType))
        {
            return response()->json('System Type not found!', 404);
        }
        try
        {
            $systemType->update($request->post());
            return response()->json('System Type Successfully Updated!', 200);
        }
        catch (\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'STUPDATE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function store(Request $request)
    {
        $input = $request->post();
        $util = new UtilitiesController();
        $duplicate = SystemType::where([
            'systemType' => $input['systemType'],
        ])->first();
        if(empty($input['description']))
        {
            $input['description'] = 'No Data';
        }
        if(! empty($duplicate))
        {
            return response()->json('System Type has been added previously!', 404);
        }
        try
        {
            $input['dateAdded'] = date('m-d-y');
            SystemType::create($input);
            return response()->json('You have added new System Type!', 200);
        }
        catch(\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'STSTORE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function destroy($id)
    {
        $unit = SystemType::find($id);
        if(empty($unit))
        {
            return response()->json('System Type not found!', 404);
        }
        $unit->delete();
        return response()->json('Successfully deleted!', 200);
    }
}
