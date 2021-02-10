<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19/11/2020
 * Time: 6:31 PM
 */

namespace App\Http\Controllers;


use App\Models\FaultCode;
use Illuminate\Http\Request;

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
            $faultCodes = 'No Record(s) Found!';
        }

        return view('references.faultcode.index', ['data' => $faultCodes, 'sidebar_selected' => 'References', 'reference_nav_selected' => 'faultcodes']);
    }
    public function show($id)
    {
        $fc = FaultCode::find($id);
        if(empty($fc))
        {
            return redirect(route('faultcode.show'))->with('response', $this->util->wrapMessage('error', 'Fault Code not found!'));
        }
        return view('reference.faultcode.show', ['data' => $fc]);
    }
    public function updateShow($id)
    {
        $fc = FaultCode::find($id);
        if(empty($fc))
        {
            return redirect(route('faultcodes'))->with('response', $this->util->wrapMessage('error', 'Fault Code not found!'));
        }
        return view('reference.faultcode.updated', ['data' => $fc]);
    }
    public function update($id, Request $request)
    {
        $fc = FaultCode::find($id);
        $util = new UtilitiesController();
        if(empty($fc))
        {
            return redirect(route('faultcodes'))->with('response', $this->util->wrapMessage('error', 'Fault Code not found!'));
        }
        try
        {
            $fc->update($request->post());
            return redirect('/reference/faultcode/'. $fc->id)
                    ->with('data',
                        [
                            'purpose' => $fc,
                            'response' => $this->util->wrapMessage('success', 'You have updated this Fault Code!') ]
                    );
        }
        catch (\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'FCUPDATE',
                $exception->getMessage()
            ]);
            return redirect('/reference/faultcode/show/'. $fc->id)->with('response', $this->util->wrapMessage('error', 'Oops something went wrong! Please contact your administrator.'));
        }
    }
    public function create()
    {
        return view('reference.faultcode.create');
    }
    public function store(Request $request)
    {
        $util = new UtilitiesController();
        $duplicate = FaultCode::where([
            'code' => $request->input('code'),
            'type' => $request->input('type')
        ])->first();

        if(! empty($duplicate))
        {
            return redirect(route('/reference/faultcode/create'))->with('response', $this->util->wrapMessage('warning', 'Fault Code has been added previously!'));
        }
        try
        {
            $fc = new FaultCode();
            $fc->code = $request->input('code');
            $fc->type = $request->input('type');
            $fc->description = $request->input('description');
            $fc->save();
            return redirect('/reference/faultcode/show/'. $fc->id)->with('response', $this->util->wrapMessage('success', 'You have added new Fault Code!'));
        }
        catch(\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'FCSTORE',
                $exception->getMessage()
            ]);
            return redirect(router('unit'))->with('response', $this->util->wrapMessage('error', 'Oops something went wrong! Please contact your administrator.'));
        }
    }

}
