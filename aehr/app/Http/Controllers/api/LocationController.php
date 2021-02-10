<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 26/11/2020
 * Time: 7:16 PM
 */

namespace App\Http\Controllers\api;
use App\Http\Controllers\UtilitiesController;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }

    public function index()
    {
        $locations = Location::orderBy('location', 'ASC')->get();
        if(empty($locations))
        {
            return response()->json('No Record(s) Found!', 404);
        }
        return response()->json($locations, 200);
    }
    public function show($id)
    {
        $location = Location::find($id);
        if(empty($location))
        {
            return response()->json('Location not found!', 404);
        }
        return response()->json($location, 200);
    }
    public function update($id, Request $request)
    {
        $location = Location::find($id);
        $util = new UtilitiesController();
        $duplicate = Location::where([
            'location' => $request->input('location'),
        ])->first();

        if(! empty($duplicate))
        {
            if($location->location != $request->input('location'))
            {
                return response()->json('Entry that you want to push through will cause duplication!!', 404);
            }
        }
        if(empty($location))
        {
            return response()->json('Location not found!', 404);
        }
        try
        {
            $location->update($request->post());
            return response()->json('Location Successfully Updated!', 200);
        }
        catch (\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'LOCUPDATE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function store(Request $request)
    {
        $input = $request->post();
        $util = new UtilitiesController();
        $duplicate = Location::where([
            'location' => $input['location'],
        ])->first();
        if(empty($input['description']))
        {
            $input['description'] = 'No Data';
        }
        if(! empty($duplicate))
        {
            return response()->json('Location has been added previously!', 404);
        }
        try
        {
            $input['dateAdded'] = date('Y-m-d');
            Location::create($input);
            return response()->json('You have added new Location!', 200);
        }
        catch(\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'LOCSTORE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function destroy($id)
    {
        $unit = Location::find($id);
        if(empty($unit))
        {
            return response()->json('Location not found!', 404);
        }
        $unit->delete();
        return response()->json('Successfully deleted!', 200);
    }
}
