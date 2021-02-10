<?php
/**
 * Created by PhpStorm.
 * User: DOH-VIN
 * Date: 26/11/2020
 * Time: 5:06 PM
 */

namespace App\Http\Controllers\api;
use App\Http\Controllers\UtilitiesController;
use App\Models\Purpose;
use Illuminate\Http\Request;

class PurposeController
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }

    public function index()
    {
        $purposes = Purpose::orderBy('purpose', 'ASC')->get();
        if(empty($purposes->toArray()))
        {
            return response()->json('No Record(s) Found!', 404);
        }
        return response()->json($purposes, 200);
    }
    public function show($id)
    {
        $purpose = Purpose::find($id);
        if(empty($purpose))
        {
            return response()->json('Purpose not found!', 404);
        }
        return response()->json($purpose, 200);
    }
    public function update($id, Request $request)
    {
        $purpose = Purpose::find($id);
        $util = new UtilitiesController();
        $duplicate = Purpose::where([
            'purpose' => $request->input('purpose'),
        ])->first();

        if(! empty($duplicate))
        {
            if($purpose->purpose != $request->input('purpose'))
            {
                return response()->json('Entry that you want to push through will cause duplication!!', 404);
            }
        }
        if(empty($purpose))
        {
            return response()->json('Purpose not found!', 404);
        }
        try
        {
            $purpose->update($request->post());
            return response()->json('Purpose Successfully Updated!', 200);
        }
        catch (\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'PURUPDATE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function store(Request $request)
    {
        $input = $request->post();
        $util = new UtilitiesController();
        $duplicate = Purpose::where([
            'purpose' => $input['purpose'],
        ])->first();
        if(empty($input['description']))
        {
            $input['description'] = 'No Data';
        }
        if(! empty($duplicate))
        {
            return response()->json('Purpose has been added previously!', 404);
        }
        try
        {
            $input['dateAdded'] = date('Y-m-d');
            Purpose::create($input);
            return response()->json('You have added new Purpose!', 200);
        }
        catch(\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'PURSTORE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function destroy($id)
    {
        $unit = Purpose::find($id);
        if(empty($unit))
        {
            return response()->json('Purpose not found!', 404);
        }
        $unit->delete();
        return response()->json('Successfully deleted!', 200);
    }
}
