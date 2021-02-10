<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }

    public function index()
    {
        $status = Status::orderBy('status', 'ASC')->get();
        if(empty($status))
        {
            $status = $this->util->wrapMessage('error', 'No Record(s) Found!');
        }
        return view('reference.status.index', ['data' => $status]);
    }
    public function show($id)
    {
        $status = Status::find($id);
        if(empty($status))
        {
            return redirect(route('status'))->with('response', $this->util->wrapMessage('error', 'Status not found!'));
        }
        return view('reference.status.show', ['data' => $status]);
    }
    public function create()
    {
        return view('reference.status.create');
    }
    public function store(Request $request)
    {
        //check for duplicate
        $duplicate = status::where('status', $request->input('status'))->first();

        if(! empty($duplicate))
        {
            return redirect(route('status.create'))->with('response', $this->util->wrapMessage('warning', 'status has been added previously!'));
        }
        try
        {
            $status = new status();
            $status->status = $request->input('status');
            $status->description = $request->input('description');
            $status->save();

            return redirect('/reference/status/show/'. $status->id)->with('response', $this->util->wrapMessage('success', 'You have created new status!'));
        }
        catch(\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'statusSTORE',
                $exception->getMessage()
            ]);
            return redirect(router('status'))->with('response', $this->util->wrapMessage('error', 'Oops something went wrong! Please contact your administrator.'));
        }
    }
    public function updateShow($id)
    {
        $status = status::find($id);
        if(empty($status))
        {
            return redirect(route('status'))->with('response', $this->util->wrapMessage('error', 'status not found!'));
        }
        return view('reference.status.update', ['data' => $status]);
    }
    public function update($id, Request $request)
    {
        $status = status::find($id);
        if(empty($status))
        {
            return redirect(route('status'))->with('response', $this->util->wrapMessage('error', 'status not found!'));
        }
        try
        {
            $status->update($request->post());
            return redirect('/reference/status/show/'. $status->id)->with('response', $this->util->wrapMessage('success', 'You have updated this status!'));
        }
        catch (\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'statusUPDATE',
                $exception->getMessage()
            ]);
            return redirect('/reference/status/show/'. $status->id)->with('response', $this->util->wrapMessage('error', 'Oops something went wrong! Please contact your administrator.'));
        }
    }
    public function destroy($id)
    {
        $status = status::find($id);
        if(empty($status))
        {
            return redirect(route('status'))->with('response', $this->util->wrapMessage('error', 'status not found!'));
        }
        $status->delete();
        return redirect(route('status'))->with('response', $this->util->wrapMessage('success', 'status has been deleted!'));
    }
}
