<?php
/**
 * Created by PhpStorm.
 * User: DOH-VIN
 * Date: 26/11/2020
 * Time: 2:09 PM
 */

namespace App\Http\Controllers\api;
use App\Http\Controllers\UtilitiesController;
use App\Models\TypeOfService;
use Illuminate\Http\Request;

class TSController
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }

    public function index()
    {
        $TypeOfServices = TypeOfService::orderBy('typeOfService', 'ASC')->get();
        if(empty($TypeOfServices->toArray()))
        {
            return response()->json('No Record(s) Found!', 404);
        }
        return response()->json($TypeOfServices, 200);
    }
    public function show($id)
    {
        $TypeOfService = TypeOfService::find($id);
        if(empty($TypeOfService))
        {
            return response()->json('Type of Service not found!', 404);
        }
        return response()->json($TypeOfService, 200);
    }
    public function update($id, Request $request)
    {
        $TypeOfService = TypeOfService::find($id);
        $util = new UtilitiesController();
        $duplicate = TypeOfService::where([
            'typeOfService' => $request->input('typeOfService'),
        ])->first();

        if(! empty($duplicate))
        {
            if($TypeOfService->typeOfService != $request->input('typeOfService'))
            {
                return response()->json('Entry that you want to push through will cause duplication!!', 404);
            }
        }
        if(empty($TypeOfService))
        {
            return response()->json('Type of Service not found!', 404);
        }
        try
        {
            $TypeOfService->update($request->post());
            return response()->json('Type of Service Successfully Updated!', 200);
        }
        catch (\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'TSUPDATE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function store(Request $request)
    {
        $input = $request->post();
        $util = new UtilitiesController();
        $duplicate = TypeOfService::where([
            'typeOfService' => $input['typeOfService'],
        ])->first();
        if(empty($input['description']))
        {
            $input['description'] = 'No Data';
        }
        if(! empty($duplicate))
        {
            return response()->json('Type of Service has been added previously!', 404);
        }
        try
        {
            $input['dateAdded'] = date('m-d-y');
            TypeOfService::create($input);
            return response()->json('You have added new Type of Service!', 200);
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
        $unit = TypeOfService::find($id);
        if(empty($unit))
        {
            return response()->json('Type of Service not found!', 404);
        }
        $unit->delete();
        return response()->json('Successfully deleted!', 200);
    }
}
