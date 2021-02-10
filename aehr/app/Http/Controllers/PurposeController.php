<?php

namespace App\Http\Controllers;

use App\Models\Purpose;
use Illuminate\Http\Request;

class PurposeController extends Controller
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }

    public function index()
    {
        $purposes = Purpose::orderBy('purpose', 'ASC')->get();
        if(empty($purposes))
        {
            $purposes = $this->util->wrapMessage('error', 'No Record(s) Found!');
        }
        return view('reference.purpose.index', ['data' => $purposes]);
    }
    public function show($id)
    {
        $purpose = Purpose::find($id);
        if(empty($purpose))
        {
            return redirect(route('purposes'))->with('response',  $this->util->wrapMessage('error','No Record(s) Found!'));
        }
        return view('reference.purpose.show', ['data' => $purpose]);
    }
    public function create()
    {
        return view('reference.purpose.create');
    }
    public function store(Request $request)
    {
        $duplicate = Purpose::where('purpose', $request->input('purpose'))->first();
        if(! empty($duplicate))
        {
            return redirect(route('purpose.create'))->with('response',  $this->util->wrapMessage('warning','This entry has been added before and might cause duplication.'));
        }
        try
        {
            Purpose::create($request->post());
            return redirect(route('purposes'))->with('response', $this->util->wrapMessage('success', 'You have created new purpose!'));
        }
        catch (\Exception $exception)
        {
            $this->util->createError([
                $_SESSION['user'],
                'PURPUPDATE',
                $exception->getMessage()
            ]);
            return redirect(route('purposes'))->with('response', $this->util->wrapMessage('error', 'Oops something went wrong! Please contact your administrator.'));
        }
    }
    public function destroy($id)
    {
        $purpose = Purpose::find($id);
        if(empty($purpose))
        {
            return redirect(route('purposes'))->with('response', $this->util->wrapMessage('error', 'No Record(s) Found!'));
        }
        $purpose->delete();
        return redirect(route('purposes'))->with('response', $this->util->wrapMessage('success', 'Entry deleted!'));
    }
}
