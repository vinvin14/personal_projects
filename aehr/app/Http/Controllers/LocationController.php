<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }

    public function index()
    {
        $location = Location::orderBy('location', 'ASC')->get();
        if(empty($location))
        {
            $location = $this->util->wrapMessage('error', 'No Record(s) Found!');
        }
        return view('reference.location.index', ['data' => $location]);
    }
    public function show($id)
    {
        $location = Location::find($id);
        if(empty($location))
        {
            return redirect(route('locations'))->with('response', $this->util->wrapMessage('error', 'No Record(s) Found!'));
        }
        return view('reference.location.show', ['data' => $location]);
    }
    public function create()
    {
        return view('reference.location.create');
    }
    public function store(Request $request)
    {
        //check for duplicate
        $duplicate = location::where('location', $request->input('location'))->first();

        if(! empty($duplicate))
        {
            return redirect(route('location.create'))->with('response', $this->util->wrapMessage('warning', 'location has been added previously!'));
        }
        try
        {
            $location = new location();
            $location->location = $request->input('location');
            $location->remarks = $request->input('remarks');
            $location->save();

            return redirect('/reference/location/show/'. $location->id)->with('response', $this->util->wrapMessage('success', 'You have created new location!'));
        }
        catch(\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'LOCSTORE',
                $exception->getMessage()
            ]);
            return redirect(router('locations'))->with('response', $this->util->wrapMessage('error', 'Oops something went wrong! Please contact your administrator.'));
        }
    }
    public function updateShow($id)
    {
        $location = Location::find($id);
        if(empty($location))
        {
            return redirect(route('locations'))->with('response', $this->util->wrapMessage('error', 'No Record(s) Found!'));
        }
        return view('reference.location.update', ['data' => $location]);
    }
    public function update($id, Request $request)
    {
        $location = Location::find($id);
        if(empty($location))
        {
            return redirect(route('locations'))->with('response', $this->util->wrapMessage('error', 'No Record(s) Found!'));
        }
        try
        {
            $location->update($request->post());
            return redirect('/reference/location/show/'. $location->id)->with('response', $this->util->wrapMessage('success', 'You have updated this location!'));
        }
        catch (\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'LOCUPDATE',
                $exception->getMessage()
            ]);
            return redirect('/reference/location/show/'. $location->id)->with('response', $this->util->wrapMessage('error', 'Oops something went wrong! Please contact your administrator.'));
        }
    }
    public function destroy($id)
    {
        $location = Location::find($id);
        if(empty($location))
        {
            return redirect(route('locations'))->with('response', $this->util->wrapMessage('error', 'No Record(s) Found!'));
        }
        $location->delete();
        return redirect(route('locations'))->with('response', $this->util->wrapMessage('success', 'location has been deleted!'));
    }
}
